<?php

namespace App\Controller\Admin;

use App\Entity\Prestataire;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class PrestataireCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestataire::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('user', 'Nom du prestataire')
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser()->getName();
                })
                ->onlyOnIndex(),
            AssociationField::new('user', 'Prénom du prestataire')
                ->formatValue(function ($value, $entity) {
                    return $entity->getUser()->getSurname();
                })
                ->onlyOnIndex(),
            TextField::new('type', 'Type de prestation'),
            NumberField::new('tarif', 'Tarif/h en €')
                ->setNumDecimals(2)
                ->setStoredAsString(false),
            CollectionField::new('prestations', 'Prestations')
                ->setTemplatePath('admin/prestataire_prestations.html.twig')
                ->onlyOnDetail(),
                BooleanField::new('verified', 'Vérifié'),
        ];
    }
}
