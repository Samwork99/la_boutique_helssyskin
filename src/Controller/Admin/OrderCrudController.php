<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    // Afin de pouvoir ajouter une "action" dans mon easyAdmin, je saisis ici ma function configureActions
    public function configureActions(Actions $actions): actions
    {
        return $actions
            ->add('index', 'detail');
    }

    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud->setDefaultSort(['id' => 'DESC']);
    // }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt', 'Passée le'),
            TextField::new('user.fullname', 'Utilisateur'),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            BooleanField::new('isPaid', 'Payée'),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()

        ];
    }
    
}
