<?php

namespace App\Controller\Admin;

use App\Entity\DemandeReservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

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
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
        ];
    }
}
