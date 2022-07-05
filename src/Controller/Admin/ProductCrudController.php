<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('name', 'Nom'),
            AssociationField::new('category', 'Categories'),
            TextField::new('subtitle', 'Sous-titres'),
            ImageField::new('illustration')
                ->setBasePath('img/img_products')
                ->setUploadDir('public/img/img_products')
                // ->setUploadedFileNamePattern('[randomhash],[extension]')
                ->setRequired(false),
            TextareaField::new('description'),
            MoneyField::new('price', 'Prix')->setCurrency('EUR'),  
        ];
    }

}

