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

class AddListingType extends AbstractType
{
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
            ->add('regionId', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'name',
                'label' => 'Region',
                'placeholder' => 'Select a region'
            ])
            ->add('address', TextType::class, [
                'label' => 'Address'
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
