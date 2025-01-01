<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Neighborhood;
use App\Entity\RealEstate;
use App\Form\AddListingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListingController extends AbstractController
{
    #[isGranted('ROLE_ADMIN')]
    #[Route('/add-listing', name: 'app_listing')]
    public function listing(Request $request, EntityManagerInterface $entityManager): Response
    {
        $estate = new RealEstate();
        $user = $this->getUser();
        $form = $this->createForm(AddListingType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $cityName = $form->get('city')->getData();
            $neighborhoodName = $form->get('neighborhood')->getData();

            // Check if the city already exists
            $city = $entityManager->getRepository(City::class)->findOneBy(['cityName' => $cityName]);

            // If city does not exist, create a new one
            if (!$city) {
                $city = new City();
                $city->setCityName($cityName);
                $entityManager->persist($city);
            }

            $neighborhood = $entityManager->getRepository(Neighborhood::class)->findOneBy(['neighborhoodName' => $neighborhoodName]);

            if (!$neighborhood){
                $neighborhood = new Neighborhood();
                $neighborhood->setNeighborhoodName($neighborhoodName);
                $neighborhood->setCity($city);
                $entityManager->persist($neighborhood);
            }

            $estate->setCity($city);
            $estate->setNeighborhood($neighborhood);
            $estate->setUserId($user);
            $estate->setDateAddedAt(new \DateTimeImmutable());
            $estate->setActive(true);

            $entityManager->persist($estate);
            $entityManager->flush();
            return $this->redirectToRoute('app_listing');
        }

        return $this->render('listing/add-listing.html.twig', [
            'realEstateForm' => $form->createView(),
        ]);
    }
}
