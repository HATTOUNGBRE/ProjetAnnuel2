<?php
// src/Controller/ReservationVoyageurController.php

namespace App\Controller;

use App\Entity\ReservationVoyageur;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ReservationVoyageurRepository;

class ReservationVoyageurController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ReservationVoyageurRepository $reservationVoyageurRepository, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->reservationVoyageurRepository = $reservationVoyageurRepository;
        $this->serializer = $serializer;
    }

    #[Route('/api/reservations', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(Request $request, PropertyRepository $propertyRepository, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $propertyId = $data['property'] ?? null;
        $property = $propertyRepository->find($propertyId);

        if (!$property) {
            return new JsonResponse(['message' => 'Property not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $reservation = new ReservationVoyageur();
        $reservation->setDateArrivee(new \DateTime($data['dateArrivee']));
        $reservation->setDateDepart(new \DateTime($data['dateDepart']));
        $reservation->setGuestNb($data['guestNb']);
        $reservation->setProperty($property);

        $errors = $validator->validate($reservation);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Reservation created successfully'], JsonResponse::HTTP_CREATED);
    }

   

    #[Route('/api/properties/{id}/reservations', name: 'get_property_reservations', methods: ['GET'])]
    public function getPropertyReservations(int $id): JsonResponse
    {
        $reservations = $this->reservationVoyageurRepository->findBy(['property' => $id]);

        $responseData = $this->serializer->serialize($reservations, 'json', ['groups' => 'reservation:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
    }
}
