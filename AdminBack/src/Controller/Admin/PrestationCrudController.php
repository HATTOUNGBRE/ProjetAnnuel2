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
            TextField::new('titre'),
            TextEditorField::new('description'),
            ChoiceField::new('type')->setChoices([
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
            DateTimeField::new('dateDeCreation')->hideOnForm(),
            DateTimeField::new('dateDEffet'),
            TextField::new('statut')->hideOnForm(),
            BooleanField::new('active'),
            AssociationField::new('user')
                ->setCrudController(UserCrudController::class)
                ->hideOnForm(),
        
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }
        $user = $this->getUser();
        $entityInstance->setUser($user);

        $entityInstance->setDateDeCreation(new \DateTime());
        $entityInstance->setStatut('En attente');



        // Persister la prestation et la réservation
        $manager->persist($entityInstance);
        $manager->flush();
    }

    public function updateEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        $entityInstance->setDateDeCreation(new \DateTime());
        parent::updateEntity($manager, $entityInstance);
    }
}
