<?php

namespace App\Form;

use App\Entity\RealEstateImages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageUpload', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Upload Images',
                    'multiple' => true,
                ], //TODO da se dobavi vtora forma za snimkite
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
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RealEstateImages::class,
        ]);
    }
}