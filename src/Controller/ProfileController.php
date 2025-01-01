<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,
                            #[Autowire('public/assets/images/avatars/')] string $profilePictureDirectory ): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePicture = $form->get('profilePicture')->getData();
            if ($profilePicture) {
                $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePicture->guessExtension();
                try {
                    $profilePicture->move($profilePictureDirectory, $newFilename);
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                        $this->addFlash('error', 'An error occurred while uploading the profile picture. Please try again.');
                        $this->get('logger')->error('File upload error: ' . $e->getMessage());
                    }
                $user->setProfileImage($profilePictureDirectory . $newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('user/profile.html.twig', [
            'profileForm' => $form,
        ]);
    }
}