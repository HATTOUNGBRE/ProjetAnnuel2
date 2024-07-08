<?php

namespace App\Controller\Admin;

use App\Entity\DemandePrestation;
use App\Entity\User;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class DemandePrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DemandePrestation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre', 'Titre'),
            TextEditorField::new('description', 'Description'),
            DateTimeField::new('dateDEffet', 'Date d\'Effet'),
            ChoiceField::new('type', 'Type')->setChoices([
                'Ménage' => 'ménage',
                'Électricité' => 'électricité',
                'Plomberie' => 'plomberie',
                'Taxi' => 'taxi',
            ]),
            TextField::new('statut', 'Statut')->setDisabled(),
            AssociationField::new('user', 'Utilisateur')->setCrudController(UserCrudController::class),
        ];
    }
}
