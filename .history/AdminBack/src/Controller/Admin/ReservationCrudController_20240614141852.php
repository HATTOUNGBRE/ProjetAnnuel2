<?php

 src/Controller/Admin/ReservationCrudController.php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            DateTimeField::new('dateDeCreation')->hideOnForm(), // Set automatically
            DateTimeField::new('dateDEffet'),
            DateTimeField::new('dateDeFin'),
            TextField::new('statut'),
            BooleanField::new('active'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Reservation) return;

        // Set the creation date only on new entities
        if ($entityInstance->getDateDeCreation() === null) {
            $entityInstance->setDateDeCreation(new \DateTime());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
