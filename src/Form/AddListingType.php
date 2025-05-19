<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Feature;
use App\Entity\Property;
use App\Entity\Region;
use App\Entity\Residential;
use App\Entity\Commercial;
use App\Entity\Land;
use App\Entity\Listing;
use App\Enum\ListingTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Doctrine\ORM\EntityManagerInterface;

class AddListingType extends AbstractType
{
    private $entityManager;
    private $regionTransformer;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->regionTransformer = new RegionNameTransformer($entityManager);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $type = $options['type'] ?? null;

        $builder
            ->add('name', TextType::class, [
                'label' => 'Property Name'
            ])
            ->add('area', TextType::class, [
                'label' => 'Area (sqm)',
            ])
            ->add('cityId', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'City',
                'placeholder' => 'Select a city'
            ])
            ->add('region', TextType::class, [
                'label' => 'Region',
                'attr' => [
                    'placeholder' => 'Enter region name'
                ],
                'required' => false,
                'help' => 'Enter a region name. If it doesn\'t exist, it will be created automatically.'
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'required' => false
            ])
            ->add('price', TextType::class, [
                'label' => 'Price',
                'mapped' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'mapped' => false
            ])
            ->add('listingType', ChoiceType::class, [
                'choices' => [
                    'For Sale' => ListingTypeEnum::SALE->value,
                    'For Rent' => ListingTypeEnum::RENT->value
                ],
                'label' => 'Listing Type',
                'mapped' => false
            ])
            ->add('features', EntityType::class, [
                'class' => Feature::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => ['class' => 'features-checkboxes'],
            ])
            ->add('images', DropzoneType::class, [
                'label' => 'Property Images',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        'constraints' => [
                            new File([
                                'maxSize' => '10240k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid image format (jpg, png)',
                            ])
                        ],
                    ])
                ],
            ]);

        $builder->get('region')->addModelTransformer($this->regionTransformer);
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (!empty($data['cityId'])) {
                $cityId = $this->entityManager->getRepository(City::class)->find($data['cityId']);
                if ($cityId) {
                    $this->regionTransformer->setCityId($cityId);
                }
            }
        });

        if ($type === 'residential') {
            $builder->add('residential', ResidentialType::class, ['mapped' => false]);
        } elseif ($type === 'commercial') {
            $builder->add('commercial', CommercialType::class, ['mapped' => false]);
        } elseif ($type === 'land') {
            $builder->add('land', LandType::class, ['mapped' => false]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'type' => null,
        ]);
    }
}
