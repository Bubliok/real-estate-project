<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\EstateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EstateController extends AbstractController
{
    #[Route('/estate/{id<\d+>}', name: 'app_estate_show')]
    public function show(int $id, CityRepository $cityRepository,
    ): Response
    {
        $city = $cityRepository->find($id);

        if (!$city) {
            throw $this->createNotFoundException('The estate does not exist');
        }

        return $this->render('estate/show.html.twig', [
            'city' => $city,
        ]);
    }
}