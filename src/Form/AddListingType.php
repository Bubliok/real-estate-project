<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Neighborhood;
use App\Entity\RealEstate;
use App\Entity\RealEstateImages;
use App\Entity\RealEstateType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\BooleanFilterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\File;
use Symfony\UX\Dropzone\Form\DropzoneType;
use function Zenstruck\Foundry\get;

class AddListingType extends AbstractType
{
    public function __construct(
        private CityNameTransformer $cityNameTransformer,
        private NeighborhoodNameTransformer $neighborhoodNameTransformer,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $years = range(2025, 1900);
        $choices = array_combine($years, $years);

        $builder
            ->add('estateName', TextType::class, [
            ])
            ->add('estateArea', TextType::class, [
                'label' => 'Estate Area',
                'attr' => [
                    'placeholder' => 'sqm.',
                ],
            ])
            ->add('estateFloor')
            ->add('isFurnished', CheckboxType::class,[
                'required' => false,
            ])
            ->add('isForRent', CheckboxType::class,[
                'required' => false,
            ])
            ->add('estatePrice')
            ->add('estateAddress')
            ->add('yearBuilt', ChoiceType::class, [
                'label' => 'Year Built',
                'choices' => $choices,
                'placeholder' => 'Select Year',
            ])
            ->add('type', EntityType::class, [
                'class' => RealEstateType::class,
                'choice_label' => fn(RealEstateType $type) => sprintf('%s', $type->getTypeName()),
                'placeholder' => 'Select Type'
            ])
            ->add('city', TextType::class, [
                'invalid_message' => 'That is not a valid city name',
            ])
            ->add('neighborhood', TextType::class, [
                'invalid_message' => 'That is not a valid neighborhood name',
            ])
            ->add('estateDescription', TextareaType::class, [
                'required' => false,
            ])
                ->add('imageUpload', DropzoneType::class, [
                    'attr' => [
                        'placeholder' => '',
                  ],
//                    'data_class' => RealEstateImages::class,
                    'mapped' => false,
                    'required' => true,
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


        $builder
            ->get('city')
            ->addModelTransformer($this->cityNameTransformer);
        $builder
            ->get('neighborhood')
            ->addModelTransformer($this->neighborhoodNameTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => RealEstate::class
        ]);
    }
}
