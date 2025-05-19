<?php

namespace App\Controller\Admin;

use App\Entity\PropertyImages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PropertyImagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PropertyImages::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield AssociationField::new('propertyId')
            ->setLabel('Property')
            ->formatValue(function ($value, $entity) {
                return $entity->getPropertyId() ? $entity->getPropertyId()->getName() : '';
            });
        yield TextField::new('imagePath')
            ->setLabel('Image Path')
            ->formatValue(function ($value, $entity) {
                if (!$entity->getImagePath()) return '';
                return $entity->getImagePath();
            });
        yield ImageField::new('imagePath')
            ->setLabel('Image Preview')
            ->setBasePath('assets/images/properties')
            ->onlyOnDetail();
    }
}
