<?php

namespace App\Controller;

use App\Entity\DemandeReservation;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DemandeReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/demandes', name: 'create_demande_reservation', methods: ['POST'])]
    public function createDemandeReservation(Request $request, EntityManagerInterface $entityManager, PropertyRepository $propertyRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['dateArrivee'], $data['dateDepart'], $data['guestNb'], $data['property'])) {
            return new JsonResponse(['message' => 'Missing data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $property = $propertyRepository->find($data['property']);
        if (!$property) {
            return new JsonResponse(['message' => 'Property not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $demande = new DemandeReservation();
        $demande->setDateArrivee(new \DateTime($data['dateArrivee']));
        $demande->setDateDepart(new \DateTime($data['dateDepart']));
        $demande->setGuestNb($data['guestNb']);
        $demande->setProperty($property);
        $demande->setStatus('pending');
        $demande->setCreatedAt(new \DateTime());
        
        $entityManager->persist($demande);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/demandes/{id}', name: 'update_demande_reservation_status', methods: ['PUT'])]
    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $demande = $this->entityManager->getRepository(DemandeReservation::class)->find($id);

        if (!$demande) {
            return new JsonResponse(['message' => 'Demande not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $demande->setStatus($data['status']);
        $demande->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($demande);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Demande status updated successfully']);
    }

    #[Route('/api/demandes/property/{propertyId}', name: 'get_demandes_by_property', methods: ['GET'])]
    public function getDemandesByProperty(int $propertyId): JsonResponse
    {
        $demandes = $this->entityManager->getRepository(DemandeReservation::class)->findBy(['property' => $propertyId]);

        return new JsonResponse($demandes);
    }
}
