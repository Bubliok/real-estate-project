<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertyImages;
use App\Form\AddListingType;
use App\Form\PropertyTypeForm;
use App\Repository\CityRepository;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Enum\ListingTypeEnum;

use App\Form\MainFormType;

class PropertyController extends AbstractController
{
    #[Route('listing/select-type', name: 'app_select_property_type')]
    public function selectType(Request $request): Response
    {
        $form = $this->createForm(PropertyTypeForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $propertyType = $form->get('propertyType')->getData();

            return $this->redirectToRoute('app_add_listing', ['type' => $propertyType]);
        }

        return $this->render('listing/select-type.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/listings/{listingType}/{cityName}', name: 'app_show_listings')]
    public function show(CityRepository $cityRepository, PropertyRepository $propertyRepository, Request $request): Response
    {
        $cityName = $request->attributes->get('cityName');
        $listingTypeString = $request->attributes->get('listingType');
        $listingType = ListingTypeEnum::from($listingTypeString);
        $city = $cityRepository->findByName($cityName);

        if (!$city) {
            $this->addFlash('error', 'City not found');
            return $this->redirectToRoute('app_homepage');
        }

        $cityId = $city->getId();
        
        $propertyType = $request->query->get('propertyType', 'all');
        if (!in_array($propertyType, ['residential', 'commercial', 'land', 'all'])) {
            $propertyType = 'all';
        }
        
        $filters = [
            'propertyType' => $propertyType
        ];
        
        if ($request->query->has('sort')) {
            $filters['sort'] = $request->query->get('sort');
        }

        if ($propertyType === 'residential') {
            $residentialTypes = $request->query->get('residentialTypes');
            $filters['residentialTypes'] = $residentialTypes ? explode(',', $residentialTypes) : null;

            $bedrooms = $request->query->get('bedrooms');
            $bathrooms = $request->query->get('bathrooms');
            $features = $request->query->get('features');
            
            if (!empty($bedrooms)) {
                $filters['bedrooms'] = $bedrooms;
            }
            if (!empty($bathrooms)) {
                $filters['bathrooms'] = $bathrooms;
            }
            if (!empty($features)) {
                $filters['features'] = explode(',', $features);
            }
        } elseif ($propertyType === 'commercial') {
            $commercialTypes = $request->query->get('commercialTypes');
            $filters['commercialTypes'] = $commercialTypes ? explode(',', $commercialTypes) : null;

            $commercialFeatures = $request->query->get('commercialFeatures');
            if (!empty($commercialFeatures)) {
                $filters['commercialFeatures'] = explode(',', $commercialFeatures);
            }
        } elseif ($propertyType === 'land') {
            $landTypes = $request->query->get('landTypes');
            $filters['landTypes'] = $landTypes ? explode(',', $landTypes) : null;

            $landFeatures = $request->query->get('landFeatures');
            if (!empty($landFeatures)) {
                $filters['landFeatures'] = explode(',', $landFeatures);
            }
        }
        
        $properties = $propertyRepository->getByCityAndListingType(
            $cityId, 
            $listingTypeString,
            $filters
        );
        
        $noPropertiesFound = empty($properties);
        
        $mainForm = $this->createForm(MainFormType::class);

        $viewData = [
            'properties' => $properties,
            'cityName' => $cityName,
            'listingType' => $listingType->value,
            'propertyType' => $propertyType,
            'noPropertiesFound' => $noPropertiesFound,
            'mainForm' => $mainForm->createView(),
        ];
        
        if ($propertyType === 'residential') {
            $viewData['selectedResidentialTypes'] = $filters['residentialTypes'] ?? [];
            $viewData['selectedBedrooms'] = $filters['bedrooms'] ?? null;
            $viewData['selectedBathrooms'] = $filters['bathrooms'] ?? null;
            $viewData['selectedFeatures'] = $filters['features'] ?? [];
        } elseif ($propertyType === 'commercial') {
            $viewData['selectedCommercialTypes'] = $filters['commercialTypes'] ?? [];
            $viewData['selectedCommercialFeatures'] = $filters['commercialFeatures'] ?? [];
        } elseif ($propertyType === 'land') {
            $viewData['selectedLandTypes'] = $filters['landTypes'] ?? [];
            $viewData['selectedLandFeatures'] = $filters['landFeatures'] ?? [];
        }

        return $this->render('listing/index.html.twig', $viewData);
    }

//    ----------------------------------

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/listing/add/{type}', name: 'app_add_listing')]
    public function listing(string $type, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,
                            #[Autowire('public/assets/images/properties/')] string $imageDirectory
    ): Response
    {
        $property = new Property();
        $form = $this->createForm(AddListingType::class, $property, [
            'type' => $type,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $property->setUser($this->getUser());
                $property->setListingType(ListingTypeEnum::from($form->get('listingType')->getData()));
                $property->setPrice((float)$form->get('price')->getData());
                $property->setDescription($form->get('description')->getData());
                $property->setCreatedAt(new \DateTimeImmutable());
                $property->setModifiedAt(new \DateTimeImmutable());
                $property->setViews(0);
                
                $entityManager->persist($property);

                if ($type === 'residential') {
                    $residential = $form->get('residential')->getData();
                    if (!$residential) {
                        throw new \Exception('Residential data is missing');
                    }
                    $residential->setProperty($property);
                    $entityManager->persist($residential);
                } elseif ($type === 'commercial') {
                    $commercial = $form->get('commercial')->getData();
                    if (!$commercial) {
                        throw new \Exception('Commercial data is missing');
                    }
                    $commercial->setProperty($property);
                    $entityManager->persist($commercial);
                } elseif ($type === 'land') {
                    $land = $form->get('land')->getData();
                    if (!$land) {
                        throw new \Exception('Land data is missing');
                    }
                    $land->setProperty($property);
                    $entityManager->persist($land);
                }

                $images = $form->get('images')->getData();
                if ($images) {
                    foreach ($images as $image) {
                        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                        try {
                            $image->move($imageDirectory, $newFilename);
                        } catch (FileException $e) {
                            throw new \Exception('An error occurred while uploading the image. Please try again.');
                        }

                        $propertyImage = new PropertyImages();
                        $propertyImage->setImagePath($imageDirectory . $newFilename);
                        $propertyImage->setPropertyId($property);
                        $entityManager->persist($propertyImage);
                    }
                }

                $entityManager->flush();

                $this->addFlash('success', 'Property listing created successfully!');
                return $this->redirectToRoute('app_homepage');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
                
                $entityManager->clear();
            }
        }

        return $this->render('listing/add-listing.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/property/{id}', name: 'app_property_detail')]
    public function detail(Property $property): Response
    {
        return $this->render('property/detail.html.twig', [
            'property' => $property
        ]);
    }
}