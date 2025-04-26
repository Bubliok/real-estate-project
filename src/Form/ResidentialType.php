<?php

namespace App\Form;

use App\Entity\Residential;
use App\Enum\ResidentialBuildTypesEnum;
use App\Enum\ResidentialHeatingTypesEnum;
use App\Enum\ResidentialTypesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResidentialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('residentialType', ChoiceType::class, [
                'choices' => ResidentialTypesEnum::cases(), // Use enum cases
                'choice_label' => fn(ResidentialTypesEnum $enum) => $enum->name, // Display enum names
                'choice_value' => fn(?ResidentialTypesEnum $enum) => $enum?->value, // Map enum values
                'label' => 'Property Type',
            ])
            ->add('rooms', NumberType::class, [
                'label' => 'Total Rooms'
            ])
            ->add('bedrooms', NumberType::class, [
                'label' => 'Bedrooms'
            ])
            ->add('bathrooms', NumberType::class, [
                'label' => 'Bathrooms'
            ])
            ->add('yardArea', NumberType::class, [
                'label' => 'Yard Area (sqm)',
                'required' => false
            ])
            ->add('floor', NumberType::class, [
                'label' => 'Floor'
            ])
            ->add('totalFloors', NumberType::class, [
                'label' => 'Total Floors'
            ])
            ->add('heatingTypes', ChoiceType::class, [
                'choices' => ResidentialHeatingTypesEnum::cases(),
                'choice_label' => fn(ResidentialHeatingTypesEnum $enum) => $enum->name,
                'choice_value' => fn(?ResidentialHeatingTypesEnum $enum) => $enum?->value,
                'label' => 'Heating Type',
            ])
            ->add('furnished', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'label' => 'Furnished'
            ])
            ->add('hasParking', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'label' => 'Parking Available'
            ])
            ->add('yearBuilt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Year Built'
            ])
            ->add('buildType', ChoiceType::class, [
                'choices' => ResidentialBuildTypesEnum::cases(),
                'choice_label' => fn(ResidentialBuildTypesEnum $enum) => $enum->name,
                'choice_value' => fn(?ResidentialBuildTypesEnum $enum) => $enum?->value,
                'label' => 'Build Type',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Residential::class,
        ]);
    }
}