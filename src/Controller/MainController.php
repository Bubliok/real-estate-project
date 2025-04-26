<?php

namespace App\Controller;

use App\Form\MainFormType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(CityRepository $cityRepository, Request $request): Response
    {
        $form = $this->createForm(MainFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cityName = $form->get('search')->getData();
            $listingType = $form->get('listingType')->getData();

            $city = $cityRepository->findByName($cityName);

            if (!$city) {
                $form->get('search')->addError(new \Symfony\Component\Form\FormError('City not found'));
            } else {
                return $this->redirectToRoute('app_show_listings', [
                    'cityName' => $city->getName(),
                    'listingType' => $listingType
                ]);
            }
        }

        return $this->render('main/homepage.html.twig', [
            'mainForm' => $form->createView(),
        ]);
    }
}
