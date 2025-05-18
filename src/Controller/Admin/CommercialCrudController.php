<?php

namespace App\Controller\Admin;

use App\Entity\Commercial;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use App\Enum\CommercialTypeEnum;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommercialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commercial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // set it to null to disable and hide the search box
            ->setSearchFields(null)
            // call this method to focus the search input automatically when loading the 'index' page
            ->setAutofocusSearch()
            ->setPaginatorUseOutputWalkers(true)
            ->setPaginatorFetchJoinCollection(true)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
//        yield AssociationField::new('property.id');
        yield IntegerField::new('rooms');
        yield IntegerField::new('bathrooms');
        yield IntegerField::new('floor');
        yield IntegerField::new('levels');
        yield BooleanField::new('isFurnished');
        yield BooleanField::new('hasParking');
        yield DateField::new('yearBuilt');
        yield ChoiceField::new('commercialType')
            ->setChoices(array_combine(
                array_map(fn($e) => $e->name, CommercialTypeEnum::cases()),
                CommercialTypeEnum::cases()
            ));
    }
}
