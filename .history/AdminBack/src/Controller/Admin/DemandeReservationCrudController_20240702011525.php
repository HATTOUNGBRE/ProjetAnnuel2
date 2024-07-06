<?php

namespace App\Controller\Admin;

use App\Entity\DemandeReservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class DemandeReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DemandeReservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->hideOnForm(),
            DateField::new('dateArrivee'),
            DateField::new('dateDepart'),
            IntegerField::new('guestNb'),
            AssociationField::new('property'),
            TextField::new('status'),
            TextField::new('name')->hideOnForm(),
            TextField::new('surname')->hideOnForm(),
            IntegerField::new('voyageurId')->hideOnForm(),
            MoneyField::new('totalPrice')->setCurrency('EUR')->setStoredAsCents(false),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            BooleanField::new('active'),
        ];
    }
}
