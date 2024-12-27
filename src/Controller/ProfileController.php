<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrPController extends AbstractController
{
    #[Route('/pr-p')]
    public function index(): Response
    {
        return $this->render('pr_p/index.html.twig');
    }
}
