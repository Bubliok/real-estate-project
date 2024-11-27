<?php

namespace App\Controller;

use App\Repository\EstateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(EstateRepository $estateRepository): Response
    {
        $estates = $estateRepository->findAll();
//        $estateCount = count($estates);
        $myEstate = $estates[array_rand($estates)];

        return $this->render('main/homepage.html.twig', [
            'estates' => $estates,
            'myEstate' => $myEstate
        ]);
    }
}