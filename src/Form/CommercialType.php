<?php

namespace App\Form;

use App\Entity\Commercial;
use App\Enum\CommercialTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommercialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commercialType', ChoiceType::class, [
                'choices' => CommercialTypeEnum::cases(),
                'choice_label' => fn(CommercialTypeEnum $enum) => $enum->name,
                'choice_value' => fn(?CommercialTypeEnum $enum) => $enum?->value,
                'label' => 'Commercial Type',
            ])
            ->add('rooms', NumberType::class, [
                'label' => 'Total Rooms'
            ])
            ->add('bathrooms', NumberType::class, [
                'label' => 'Bathrooms'
            ])
            ->add('floor', NumberType::class, [
                'label' => 'Floor'
            ])
            ->add('levels', NumberType::class, [
                'label' => 'Levels'
            ])
            ->add('isFurnished', ChoiceType::class, [
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
                'label' => 'Parking'
            ])
            ->add('yearBuilt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Year Built'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commercial::class,
        ]);
    }
} 