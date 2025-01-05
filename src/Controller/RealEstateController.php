<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\RealEstate;
use App\Repository\RealEstateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RealEstateController extends AbstractController
{
//    #[Route('/real-estate/{id<\d+>}', name: 'app_estate_show')]
    #[Route('/real-estate/{cityId<\d+>}', name: 'app_estate_show')]
    public function show( int $cityId, RealEstateRepository $realEstateRepository): Response
    {
        $realEstates = $realEstateRepository->findByCityId($cityId);

        if (!$realEstates) {
            throw $this->createNotFoundException('Real estate not found');
        }

//        $responseContent = '';
//        foreach ($realEstates as $estate) {
//            $responseContent .= 'Real estate with name: ' . $estate->getEstateName() . '<br>';
//        }

//        return new Response($responseContent);

        return $this->render('estate/show.html.twig', [
            'realEstates' => $realEstates,
        ]);
    }

    #[Route('/real-estate/listing/{id<\d+>}', name: 'app_estate_detail')]
    public function detail(int $id, RealEstateRepository $realEstateRepository): Response
    {
        $estate = $realEstateRepository->find($id);

        if (!$estate) {
            throw $this->createNotFoundException('Real estate not found');
        }

        return $this->render('estate/detail.html.twig', [
            'estate' => $estate,
        ]);
    }
}
