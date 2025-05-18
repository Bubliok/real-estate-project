<?php

namespace App\Controller\Admin;

use App\Entity\Agency;
use App\Entity\City;
use App\Entity\Commercial;
use App\Entity\Feature;
use App\Entity\Land;
use App\Entity\Property;
use App\Entity\PropertyImages;
use App\Entity\Region;
use App\Entity\Residential;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{
    #[isGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
//        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
//         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
//         return $this->redirect($adminUrlGenerator->setController(CityCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->renderContentMaximized()
            ->setTitle('IMT Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Homepage', 'fa fa-home', $this->generateUrl('app_homepage'));
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('City', 'fas fa-city', City::class);
        yield MenuItem::linkToCrud('Region', 'fas fa-neighborhood', Region::class);
        yield MenuItem::linkToCrud('Properties', 'fas fa-real-estates', Property::class);
        yield MenuItem::linkToCrud('Property images', 'fas fa-images', PropertyImages::class);
//        yield MenuItem::linkToCrud('Commercial', 'fas fa-commercial', Commercial::class);
//        yield MenuItem::linkToCrud('Land', 'fas fa-land', Land::class);
//        yield MenuItem::linkToCrud('Residential', 'fas fa-residential', Residential::class);
        yield MenuItem::linkToCrud('Features', 'fas fa-feature', Feature::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Agency', 'fas fa-agency', Agency::class);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);

    }


}
