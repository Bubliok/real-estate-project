<?php

namespace App\Controller;

use App\Repository\EstateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class EstateController extends AbstractController
{
    #[Route('/estate/{id<\d+>}', name: 'app_estate_show')]
    public function show(int $id, EstateRepository $repository)
    {
        $estate = $repository->find($id);

        if (!$estate) {
            throw $this->createNotFoundException('The estate does not exist');
        }

        return $this->render('estate/show.html.twig', [
            'estate' => $estate,
        ]);
    }
}