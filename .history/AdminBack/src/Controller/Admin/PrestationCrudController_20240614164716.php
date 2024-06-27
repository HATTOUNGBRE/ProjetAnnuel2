<?php

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            ]),
            DateTimeField::new('dateDeCreation')->hideOnForm(),
            DateTimeField::new('dateDEffet'),
            TextField::new('statut')->hideOnForm(),
            BooleanField::new('active'),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        $entityInstance->setDateDeCreation(new \DateTime());
        $entityInstance->setStatut('En attente');
       

        // Créer une nouvelle réservation associée à cette prestation
        $reservation = new Reservation();
        $reservation->setTitre($entityInstance->getTitre());
        $reservation->setDateDeCreation(new \DateTime());
        $reservation->setDateDEffet($entityInstance->getDateDEffet());
        $reservation->setDateDeFin(new \DateTime());
        $reservation->setStatut('En attente');
        $reservation->setActive(true);
        $reservation->setValide(false);
        $reservation->setPrestation($entityInstance);

        // Persister la prestation et la réservation
        $manager->persist($entityInstance);
        $manager->persist($reservation);
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
