<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\City;
use App\Entity\RealEstate;
use App\Repository\RealEstateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RealEstateController extends AbstractController
{
//    #[Route('/real-estate/{id<\d+>}', name: 'app_estate_show')]
    #[Route('/real-estate/{cityId<\d+>}', name: 'app_estate_show')]
    public function show( int $cityId, RealEstateRepository $realEstateRepository, Request $request): Response
    {

        $sort = $request->query->get('sort', 'price_asc');
        $isFurnished = $request->query->getBoolean('isFurnished');
        $isForRent = $request->query->getBoolean('isForRent');

        $realEstates = $realEstateRepository->findByCityIdSortedFurnishedForRent($cityId, $isFurnished, $isForRent, $sort);
//        if (!$realEstates) {
//            throw $this->createNotFoundException('Real estate not found');
//        }
        $noEstatesFound = empty($realEstates);


        return $this->render('estate/show.html.twig', [
            'realEstates' => $realEstates,
            'sort'=>$sort,
            'isFurnished'=>$isFurnished,
            'isForRent'=> $isForRent,
            'noEstatesFound'=>$noEstatesFound,
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
