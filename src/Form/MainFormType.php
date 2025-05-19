<?php

namespace App\Form;

use App\Enum\CommercialTypeEnum;
use App\Enum\LandZoningTypeEnum;
use App\Enum\ResidentialTypesEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, [
                'label' => 'Search',
                'attr' => [
                    'placeholder' => 'Search for a city'
                ]
            ])
            ->add('listingType', ChoiceType::class, [
                'choices' => [
                    'rent' => 'rent',
                    'sale' => 'sale',
                ]
            ])
            ->add('residentialTypes', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($case) => ucfirst($case->value), ResidentialTypesEnum::cases()),
                    array_map(fn($case) => $case->value, ResidentialTypesEnum::cases())
                ),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'property-type-select']
            ])
            ->add('commercialTypes', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($case) => ucfirst($case->value), CommercialTypeEnum::cases()),
                    array_map(fn($case) => $case->value, CommercialTypeEnum::cases())
                ),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'property-type-select']
            ])
            ->add('landTypes', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn($case) => ucfirst($case->value), LandZoningTypeEnum::cases()),
                    array_map(fn($case) => $case->value, LandZoningTypeEnum::cases())
                ),
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => ['class' => 'property-type-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'Hmm, user not found!',
        ]);
    }
}
