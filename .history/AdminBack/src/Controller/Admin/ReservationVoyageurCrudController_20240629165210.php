<?php

namespace App\Controller\Admin;

use App\Entity\ReservationVoyageur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ReservationVoyageurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ReservationVoyageur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('dateArrivee', 'Date d\'arrivée'),
            DateField::new('dateDepart', 'Date de départ'),
            IntegerField::new('guestNb', 'Nombre de personnes'),
            AssociationField::new('property', 'Propriété'),
        ];
    }
}
