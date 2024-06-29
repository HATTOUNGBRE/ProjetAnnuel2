<?php

namespace App\Controller;

use App\Entity\DemandeReservation;
use App\Entity\Property;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PropertyRepository;
use Psr\Log\LoggerInterface;
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
    public function createDemandeReservation(
        Request $request, 
        EntityManagerInterface $entityManager, 
        PropertyRepository $propertyRepository, 
        ValidatorInterface $validator,
        LoggerInterface $logger
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            $logger->error('Invalid JSON data');
            return new JsonResponse(['message' => 'Invalid JSON data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $logger->info('Received data for reservation', $data);

        $property = $propertyRepository->find($data['property']);
        if (!$property) {
            $logger->error('Property not found', ['propertyId' => $data['property']]);
            return new JsonResponse(['message' => 'Property not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $demande = new DemandeReservation();
        $demande->setDateArrivee(new \DateTime($data['dateArrivee']));
        $demande->setDateDepart(new \DateTime($data['dateDepart']));
        $demande->setGuestNb($data['guestNb']);
        $demande->setProperty($property);
        $demande->setName($data['name']);
        $demande->setSurname($data['surname']);
        $demande->setVoyageurId($data['voyageurId']);
        $demande->setStatus('En attente');
        $demande->setCreatedAt(new \DateTime());

        $errors = $validator->validate($demande);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            $logger->error('Validation failed', ['errors' => $errorsString]);
            return new JsonResponse(['message' => $errorsString], JsonResponse::HTTP_BAD_REQUEST);
        }

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
