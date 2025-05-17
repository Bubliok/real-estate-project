<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\UserFavorites;
use App\Repository\PropertyRepository;
use App\Repository\UserFavoritesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavoriteController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/favorites/toggle/{slug}', name: 'app_toggle_favorite', methods: ['POST'])]
    public function toggleFavorite(
        string $slug,
        PropertyRepository $propertyRepository,
        UserFavoritesRepository $userFavoritesRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $property = $propertyRepository->findBySlug($slug);
        if (!$property) {
            throw $this->createNotFoundException('Property not found');
        }

        $user = $this->getUser();
        
        $favorite = $userFavoritesRepository->findOneBy([
            'propertyId' => $property,
            'userId' => $user
        ]);
        
        $isAjax = $request->isXmlHttpRequest();

        if ($favorite) {
            $entityManager->remove($favorite);
            $entityManager->flush();
            
            if ($isAjax) {
                return new JsonResponse([
                    'success' => true,
                    'isFavorite' => false,
                    'message' => 'Removed from favorites'
                ]);
            }
            
            $this->addFlash('success', 'Property removed from favorites');
        } else {
            $favorite = new UserFavorites();
            $favorite->setPropertyId($property);
            $favorite->setUserId($user);
            
            $entityManager->persist($favorite);
            $entityManager->flush();
            
            if ($isAjax) {
                return new JsonResponse([
                    'success' => true,
                    'isFavorite' => true,
                    'message' => 'Added to favorites'
                ]);
            }
            
            $this->addFlash('success', 'Property added to favorites');
        }
        
        return $this->redirectToRoute('app_property_detail', ['slug' => $slug]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/favorites', name: 'app_favorites')]
    public function viewFavorites(UserFavoritesRepository $userFavoritesRepository): Response
    {
        $user = $this->getUser();
        $favorites = $userFavoritesRepository->findBy(['userId' => $user]);
        
        return $this->render('favorite/favorite.html.twig', [
            'favorites' => $favorites
        ]);
    }
} 