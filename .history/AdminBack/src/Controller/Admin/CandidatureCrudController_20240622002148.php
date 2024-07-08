<?php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CandidatureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Candidature::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('prestataire', 'Prestataire'),
            AssociationField::new('reservation', 'Réservation'),
            BooleanField::new('validated', 'Validé'),
        ];
    }
}
