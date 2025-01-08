<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('isFurnished', CheckboxType::class, [
                'label' => 'Furnished',
                'required' => false,
            ])
            ->add('isForRent', CheckboxType::class, [
                'label' => 'For Rent',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
