<?php

namespace App\Controller;

use App\Entity\Estate;
use App\Repository\EstateRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/estate')]
class RealEstateApiController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function getRealEstateData( EstateRepository $repository): Response
    {
        $realEstateData = $repository->findAll();

        return $this->json($realEstateData);
    }


    #[Route('/{id<\d+>}', methods: ['GET'])]
    public function get(int $id, EstateRepository $repository) : Response
    {
        $estate = $repository->find($id);

        if (!$estate){
            throw $this->createNotFoundException('Estate not found');
        }

        return $this->json($estate);
    }
}