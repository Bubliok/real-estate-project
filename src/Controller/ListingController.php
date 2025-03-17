<?php

namespace App\Controller;

use App\Enum\City;
use App\Enum\Favourites;
use App\Enum\Neighborhood;
use App\Enum\RealEstate;
use App\Enum\RealEstateImages;
use App\Form\AddListingType;
use App\Form\ImageUploadFormType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class ListingController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
}
    #[isGranted('ROLE_ADMIN')]
    #[Route('/add-listing', name: 'app_listing')]
    public function listing(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,
                        #[Autowire('public/assets/images/estates/')] string $imageDirectory): Response
    {
        $estate = new RealEstate();
        $user = $this->getUser();
        $form = $this->createForm(AddListingType::class, $estate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $images = $form->get('imageUpload')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                    try {
                        $image->move($imageDirectory, $newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('error', 'An error occurred while uploading the image. Please try again.');
                        $this->logger->error('File upload error: ' . $e->getMessage());
                    }

                    $realEstateImage = new RealEstateImages();
                    $realEstateImage->setImagePath($imageDirectory. $newFilename);
                    $realEstateImage->setRealEstate($estate);

                    $entityManager->persist($realEstateImage);

                }
            }

//            $estate->addRealEstateImage($realEstateImage);
            $estate = $form->getData();
            $estate->setUserId($user);
            $estate->setDateAddedAt(new \DateTimeImmutable());
            $entityManager->persist($estate);
            $entityManager->flush();
            return $this->redirectToRoute('app_listing');
        }

        return $this->render('listing/add-listing.html.twig', [
            'realEstateForm' => $form->createView(),
        ]);
    }


    #[IsGranted('ROLE_ADMIN')]
    #[Route('/listing/delete/{id}', name: 'listing_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $estate = $entityManager->getRepository(RealEstate::class)->find($id);

        if (!$estate) {
            $this->addFlash('error', 'Listing not found.');
            return $this->redirectToRoute('app_listing');
        }

        if ($this->isCsrfTokenValid('delete' . $estate->getId(), $request->request->get('_token'))) {
            // Delete related images
            $images = $estate->getRealEstateImages();
            foreach ($images as $image) {
                $entityManager->remove($image);
            }

            $entityManager->remove($estate);
            $entityManager->flush();
            $this->addFlash('success', 'Listing and related images deleted successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_listing');
    }
}
