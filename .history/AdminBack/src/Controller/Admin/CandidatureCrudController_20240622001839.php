<?php
// src/Controller/Admin/CandidatureCrudController.php

namespace App\Controller\Admin;

use App\Entity\Candidature;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

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
            AssociationField::new('user', 'Prestataire')
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser()->getName();
                }),
            AssociationField::new('reservation', 'Réservation')
                ->formatValue(function ($value, $entity) {
                    return $entity->getReservation()->getTitre();
                }),
            BooleanField::new('validated', 'Validé')
        ];
    }
}
