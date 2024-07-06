<?php

namespace App\Controller\Admin;

use App\Entity\DemandeReservation;
use App\Utils\Utils\ReservationNumberGenerator;
use App\Repository\DemandeReservationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;


class DemandeReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DemandeReservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('id')->hideOnForm(),
            TextField::new('reservationNumber', 'Numéro de réservation'),
            DateField::new('dateArrivee'),
            DateField::new('dateDepart'),
            IntegerField::new('guestNb'),
            AssociationField::new('property'),
            TextField::new('status'),
            TextField::new('name')->hideOnForm(),
            TextField::new('surname')->hideOnForm(),
            IntegerField::new('voyageurId')->hideOnForm(),
            MoneyField::new('totalPrice')->setCurrency('EUR')->setStoredAsCents(false),
            DateTimeField::new('createdAt')->hideOnIndex(),
            DateTimeField::new('updatedAt')->hideOnIndex(),
            BooleanField::new('active'),
        ];
    }

    #[Route('/api/demandes/active', name: 'get_active_demandes', methods: ['GET'])]
    public function getActiveDemandes(): JsonResponse
    {
        $demandes = $this->demandeRepository->findBy(['active' => true]);

        if (!$demandes) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        return $this->json($demandes, 200, [], ['groups' => 'demande:read']);
    }
}
