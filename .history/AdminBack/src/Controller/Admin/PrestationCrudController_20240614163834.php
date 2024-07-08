<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;


class PrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description'),
            ChoiceField::new('type')->setChoices([
                'Ménage' => 'ménage',
                'Électricité' => 'électricité',
                'Plomberie' => 'plomberie',
                'Taxi' => 'taxi',
            ]),
            DateTimeField::new('dateDeCreation')->hideOnForm(),
            DateTimeField::new('dateDEffet'),
            ChoiceField::new('statut')->setChoices([
                'Accepté' => 'accepté',
                'Refusé' => 'refusé',
                'Annulé' => 'annulé',
                'Terminé' => 'terminé',
                'En attente' => 'en attente',
            ]),
            BooleanField::new('active'),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        $entityInstance->setDateDeCreation(new \DateTime());
        parent::persistEntity($manager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        $entityInstance->setDateDeCreation(new \DateTime());
        parent::updateEntity($manager, $entityInstance);
    }

    // 
}
