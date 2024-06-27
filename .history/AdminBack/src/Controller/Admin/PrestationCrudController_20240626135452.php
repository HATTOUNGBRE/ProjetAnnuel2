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
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;

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
            TextField::new('titre', 'Titre'),
            TextEditorField::new('description', 'Description'),
            ChoiceField::new('type', 'Type')->setChoices([
                'Ménage' => 'ménage',
                'Électricité' => 'électricité',
                'Plomberie' => 'plomberie',
                'Taxi' => 'taxi',
                'Coursier' => 'coursier',
                'Gardien' => 'gardien',
                'Jardinage' => 'jardinage',
                'Réparation' => 'réparation',
                'Blanchisserie' => 'blanchisserie',
            ]),
            DateTimeField::new('dateDeCreation', 'Date de Création')->hideOnForm(),
            DateTimeField::new('dateDEffet', 'Date d\'Effet'),
            DateTimeField::new('dateDeFin', 'Date de Fin'),
            TextField::new('statut', 'Statut')->hideOnForm(),
            BooleanField::new('active', 'Active'),
            AssociationField::new('user', 'Utilisateur')
                ->setCrudController(UserCrudController::class)
                ->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        $entityInstance->setDateDeCreation(new \DateTime());
        $entityInstance->setStatut('En attente');

        // Persister la prestation
        $manager->persist($entityInstance);
        $manager->flush();
    }

    public function updateEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        // Assurez-vous de ne pas modifier la date de création lors de la mise à jour
        // $entityInstance->setDateDeCreation(new \DateTime()); // Supprimez cette ligne si vous ne souhaitez pas mettre à jour la date de création
        parent::updateEntity($manager, $entityInstance);
    }
}
