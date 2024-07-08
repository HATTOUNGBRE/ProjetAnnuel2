<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class ReservationCrudController extends AbstractCrudController
{
    public const ACTION_DUPLICATE = 'duplicate';

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicate = Action::new(self::ACTION_DUPLICATE)
            ->linkToCrudAction('duplicateReservation');

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicate)
            ->add(Crud::PAGE_EDIT, $duplicate)
            ->add(Crud::PAGE_DETAIL, $duplicate);}

         
    public function configureFields(string $pageName): iterable{
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('prestation'),
            DateTimeField::new('dateDeCreation')->hideOnForm(),
            DateTimeField::new('dateDEffet'),
            DateTimeField::new('dateDeFin'),
            ChoiceField::new('statut')->setChoices([
                'Accepté' => 'accepté',
                'Refusé' => 'refusé',
                'Annulé' => 'annulé',
                'Terminé' => 'terminé',
            ]),
            BooleanField::new('active'),
            BooleanField::new('valide'),
        ];
    }

    public function persistEntity(EntityManagerInterface $manager, $entityInstance): void
    {
        if (!$entityInstance instanceof Reservation) return;

        // Set the creation date only on new entities
        if ($entityInstance->getDateDeCreation() === null) {
            $entityInstance->setDateDeCreation(new \DateTime());
        }

        parent::persistEntity($manager, $entityInstance);
    }

    public function duplicateReservation(
        AdminContext $context,
        AdminUrlGenerator $adminUrlGenerator,
        EntityManagerInterface $manager): Response
    {
        /** @var Reservation $reservation */
        $reservation = $context->getEntity()->getInstance();
        $newReservation = clone $reservation;

        parent::persistEntity($manager, $newReservation);

        $url = $adminUrlGenerator
            ->setController(ReservationCrudController::class)
            ->setAction(Action::DETAIL)
            ->setEntityId($newReservation->getId())
            ->generateUrl();

        return $this->redirect($url);
    }
}
