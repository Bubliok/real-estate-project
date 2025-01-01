<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Neighborhood;
use App\Entity\RealEstate;
use App\Entity\RealEstateType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\BooleanFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddListingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $years = range(1900, 2025);
        $choices = array_combine($years, $years);
        $builder
            ->add('estateName')
            ->add('estateArea')
            ->add('estateFloor')
            ->add('isFurnished', BooleanFilterType::class,[
                'mapped' => false,
            ])
            ->add('estatePrice')
            ->add('estateAddress')
            ->add('yearBuilt',  ChoiceType::class, [
                'label'=> 'Year Built',
                'choices' => $choices,
                'placeholder' => 'Select Year',
            ])
            ->add('type', EntityType::class, [
                'class' => RealEstateType::class,
                'choice_label' => function (RealEstateType $type) {
                    return sprintf('%s',  $type->getTypeName());
                },
                'placeholder' => 'Select Type'
            ])
//            ->add('city', EntityType::class, [
//                'class' => City::class,
//                'choice_label' => function (City $city) {
//                    return sprintf('%s',  $city->getCityName());
//                },
//                'placeholder' => 'Select City'
//            ])
            ->add('city', TextType::class)

            ->add('neighborhood', TextType::class, [
                'label' => 'Neighborhood Name',
                'required' => true,
                'attr' => ['placeholder' => 'Type Neighborhood Name']
            ]);
//            ->add('neighborhood', EntityType::class, [
//                'class' => Neighborhood::class,
//                'choice_label' => function (Neighborhood $neighborhood) {
//                    return sprintf('%s',  $neighborhood->getNeighborhoodName());
//                },
//                'placeholder' => 'Select Neighborhood'
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RealEstate::class,
        ]);
    }
}
