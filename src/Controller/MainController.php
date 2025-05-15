<?php

namespace App\Controller;

use App\Form\MainFormType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(CityRepository $cityRepository, Request $request): Response
    {
        $form = $this->createForm(MainFormType::class);
        $form->handleRequest($request);

        $searchTerm = $request->query->get('search');
        if (!empty($searchTerm)) {
            $city = $cityRepository->findByName($searchTerm);
            if (!$city) {
                $this->addFlash('error', 'City not found: ' . $searchTerm);
            } else {
                $listingType = $request->query->get('listingType', 'rent');
                $propertyType = $request->query->get('propertyType', 'all');
                
                $redirectParams = [
                    'listingType' => $listingType,
                    'cityName' => $city->getName(),
                ];
                
                if ($propertyType !== 'all') {
                    $redirectParams['propertyType'] = $propertyType;
                }
                
                if ($request->query->has('sort')) {
                    $redirectParams['sort'] = $request->query->get('sort');
                }
                
                if ($propertyType === 'residential') {
                    $this->preserveQueryParams($redirectParams, $request, [
                        'residentialTypes', 'bedrooms', 'bathrooms', 'features'
                    ]);
                } elseif ($propertyType === 'commercial') {
                    $this->preserveQueryParams($redirectParams, $request, [
                        'commercialTypes', 'commercialFeatures'
                    ]);
                } elseif ($propertyType === 'land') {
                    $this->preserveQueryParams($redirectParams, $request, [
                        'landTypes', 'landFeatures'
                    ]);
                }
                
                return $this->redirectToRoute('app_show_listings', $redirectParams);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $cityName = $form->get('search')->getData();
            $listingType = $form->get('listingType')->getData();
            $activePropertyType = $request->request->get('activePropertyType', 'all');

            $city = $cityRepository->findByName($cityName);

            if (!$city) {
                $form->get('search')->addError(new FormError('City not found'));
            } else {
                $filters = $this->extractFilters($form, $request, $activePropertyType);

                $redirectParams = [
                    'cityName' => $city->getName(),
                    'listingType' => $listingType,
                    'propertyType' => $activePropertyType,
                ];

                return $this->redirectToRoute('app_show_listings', array_merge($redirectParams, $filters));
            }
        }

        return $this->render('main/homepage.html.twig', [
            'mainForm' => $form->createView(),
        ]);
    }

    private function extractFilters($form, Request $request, string $propertyType): array
    {
        $filters = [];

        if ($propertyType === 'residential') {
            $residentialTypes = $form->get('residentialTypes')->getData();
            if (!empty($residentialTypes)) {
                $filters['residentialTypes'] = implode(',', $residentialTypes);
            }
            
            $bedrooms = $request->request->get('bedrooms');
            if (!empty($bedrooms)) {
                $filters['bedrooms'] = $bedrooms;
            }
            
            $bathrooms = $request->request->get('bathrooms');
            if (!empty($bathrooms)) {
                $filters['bathrooms'] = $bathrooms;
            }
            
            $features = $request->request->all('features');
            if (!empty($features)) {
                $filters['features'] = implode(',', $features);
            }
        } elseif ($propertyType === 'commercial') {
            $commercialTypes = $form->get('commercialTypes')->getData();
            if (!empty($commercialTypes)) {
                $filters['commercialTypes'] = implode(',', $commercialTypes);
            }
            
            $commercialFeatures = $request->request->all('commercial_features');
            if (!empty($commercialFeatures)) {
                $filters['commercialFeatures'] = implode(',', $commercialFeatures);
            }
        } elseif ($propertyType === 'land') {
            $landTypes = $form->get('landTypes')->getData();
            if (!empty($landTypes)) {
                $filters['landTypes'] = implode(',', $landTypes);
            }
            
            $landFeatures = $request->request->all('land_features');
            if (!empty($landFeatures)) {
                $filters['landFeatures'] = implode(',', $landFeatures);
            }
        }

        return $filters;
    }
    private function preserveQueryParams(array &$params, Request $request, array $paramNames): void
    {
        foreach ($paramNames as $paramName) {
            if ($request->query->has($paramName)) {
                $params[$paramName] = $request->query->get($paramName);
            }
        }
    }
}