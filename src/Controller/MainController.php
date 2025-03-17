<?php

namespace App\Controller;

use App\Enum\City;
use App\Form\MainFormType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(CityRepository $cityRepository, Request $request
    ): Response {

        $form = $this->createForm(MainFormType::class);
        $form->handleRequest($request);
        $cityId = null;
        $cityName = $form->get('search')->getData();
        $isFurnished = $form->get('isFurnished')->getData();
        $isForRent = $form->get('isForRent')->getData();

        if ($cityName){
             $city = $cityRepository->findByName($cityName);
             $cityId = $city ? $city->getId() : null;

        if ($cityId) {
            return $this->redirectToRoute('app_estate_show', [
                'cityId' => $cityId,
                'isFurnished' => $isFurnished,
                'isForRent' => $isForRent,
            ]);
        }
        }

        return $this->render('main/homepage.html.twig', [
            'mainForm' => $form->createView(),
            'cityID' => $cityId,
        ]);
    }
}