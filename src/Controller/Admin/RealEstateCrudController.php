<?php

namespace App\Controller\Admin;

use App\Enum\RealEstate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RealEstateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RealEstate::class;
    }


//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            TextField::new('estateName'),
//            TextEditorField::new('estateA'),
//        ];
//    }

}
