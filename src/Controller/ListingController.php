<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Favourites;
use App\Entity\Neighborhood;
use App\Entity\RealEstate;
use App\Entity\RealEstateImages;
use App\Form\AddListingType;
use App\Form\ImageUploadFormType;
use Doctrine\ORM\EntityManagerInterface;
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
                        $this->get('logger')->error('File upload error: ' . $e->getMessage());
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
}
