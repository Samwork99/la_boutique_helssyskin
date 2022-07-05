<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('Lastname', 'Nom'),
            TextField::new('Firstname', 'Prénom'),
            EmailField::new('Email'),
            TextField::new('address', 'Adresse'),
            TextField::new('zip', 'Code postal'),
            TextField::new('city', 'Ville'),
        ];
    }

}
