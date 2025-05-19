<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Enum\ListingTypeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PropertyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Property::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield IntegerField::new('area');
        yield TextField::new('address');
        yield MoneyField::new('price')->setCurrency('EUR');
        yield TextareaField::new('description');
        yield ChoiceField::new('listingType')
            ->setChoices(array_combine(
                array_map(fn($e) => $e->name, ListingTypeEnum::cases()),
                ListingTypeEnum::cases()
            ));
        yield IntegerField::new('views')->hideOnForm();
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('modifiedAt')->hideOnForm();
        yield AssociationField::new('cityId')
            ->setLabel('City')
            ->formatValue(function ($value, $entity) {
                return $entity->getCityId() ? $entity->getCityId()->getName() : '';
            });
        yield AssociationField::new('region')
            ->setLabel('Region')
            ->formatValue(function ($value, $entity) {
                return $entity->getRegion() ? $entity->getRegion()->getName() : '';
            });
        yield AssociationField::new('user')
            ->setLabel('User')
            ->formatValue(function ($value, $entity) {
                $user = $entity->getUser();
                if (!$user) return '';
                return $user->getUsername();
            });
        yield AssociationField::new('features')
            ->setLabel('Features')
            ->formatValue(function ($value, $entity) {
                $features = $entity->getFeatures();
                if ($features->isEmpty()) return '(none)';
                
                $featureNames = [];
                foreach ($features as $feature) {
                    $featureNames[] = $feature->getName();
                }
                
                return implode(', ', $featureNames);
            });
    }
}
