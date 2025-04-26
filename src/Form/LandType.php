<?php

namespace App\Form;

use App\Entity\Land;
use App\Enum\LandZoningTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('zoningType', ChoiceType::class, [
                'choices' => LandZoningTypeEnum::cases(),
                'choice_label' => fn(LandZoningTypeEnum $enum) => $enum->name,
                'choice_value' => fn(?LandZoningTypeEnum $enum) => $enum?->value,
                'label' => 'Zoning Type',
            ])
            ->add('hasElectricity', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'label' => 'Electricity Available'
            ])
            ->add('hasWater', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false
                ],
                'label' => 'Water Available'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Land::class,
        ]);
    }
} 