<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(CityRepository $cityRepository,
    ): Response {

        $cities = $cityRepository->findSofia();
        $myCity = $cityRepository->findMyCity();

        return $this->render('main/homepage.html.twig', [
            'cities' => $cities,
            'myCity' => $myCity
        ]);
    }
}