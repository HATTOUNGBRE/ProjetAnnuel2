<?php
// src/Controller/ReservationVoyageurController.php

namespace App\Controller;

use App\Entity\ReservationVoyageur;
use App\Entity\DemandeReservation;
use App\Entity\Payment;
use App\Repository\PropertyRepository;
use App\Repository\ReservationVoyageurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationVoyageurController extends AbstractController
{
    private $entityManager;
    private $reservationVoyageurRepository;
    private $serializer;
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReservationVoyageurRepository $reservationVoyageurRepository,
        SerializerInterface $serializer,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->reservationVoyageurRepository = $reservationVoyageurRepository;
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
    }

    #[Route('/api/reservations', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation des données de réservation
        if (!isset($data['dateArrivee'], $data['dateDepart'], $data['guestNb'], $data['propertyId'], $data['voyageurId'], $data['totalPrice'], $data['paymentMethod'])) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Création de la réservation
        $reservation = new ReservationVoyageur();
        $reservation->setDateArrivee(new \DateTime($data['dateArrivee']));
        $reservation->setDateDepart(new \DateTime($data['dateDepart']));
        $reservation->setGuestNb($data['guestNb']);
        $reservation->setTotalPrice($data['totalPrice']);
        // Set the property and voyageur entities based on their IDs
        // Assuming you have corresponding repository methods to fetch these entities
        $property = $entityManager->getRepository(Property::class)->find($data['propertyId']);
        $voyageur = $entityManager->getRepository(User::class)->find($data['voyageurId']);
        $reservation->setProperty($property);
        $reservation->setVoyageurId($data['voyageurId']);

        $entityManager->persist($reservation);
        
        // Création du paiement
        $payment = new Payment();
        $payment->setDate(new \DateTime());
        $payment->setAmount($data['totalPrice']);
        $payment->setMethod($data['paymentMethod']);
        $payment->setReservation($reservation);

        $entityManager->persist($payment);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Reservation and payment created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/properties/{id}/reservations', name: 'get_property_reservations', methods: ['GET'])]
    public function getPropertyReservations(int $id): JsonResponse
    {
        $reservations = $this->reservationVoyageurRepository->findBy(['property' => $id]);

        foreach ($reservations as $reservation) {
            $voyageur = $this->userRepository->find($reservation->getVoyageurId());
            $reservation->voyageurName = $voyageur->getName();
            $reservation->voyageurSurname = $voyageur->getSurname();
        }

        $responseData = $this->serializer->serialize($reservations, 'json', ['groups' => 'reservation:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/reservations/check', name: 'check_reservations', methods: ['POST'])]
    public function checkReservations(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $propertyId = $data['propertyId'];
        $dateArrivee = new \DateTime($data['dateArrivee']);
        $dateDepart = new \DateTime($data['dateDepart']);

        $reservations = $this->reservationVoyageurRepository->createQueryBuilder('r')
            ->where('r.property = :propertyId')
            ->andWhere('r.dateArrivee < :dateDepart')
            ->andWhere('r.dateDepart > :dateArrivee')
            ->setParameter('propertyId', $propertyId)
            ->setParameter('dateArrivee', $dateArrivee)
            ->setParameter('dateDepart', $dateDepart)
            ->getQuery()
            ->getResult();

        return new JsonResponse($reservations);
    }

    #[Route('/api/demandes/{id}/status', name: 'update_demande_status', methods: ['PATCH'])]
    public function updateDemandeStatus(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $demande = $this->entityManager->getRepository(DemandeReservation::class)->find($id);

        if (!$demande) {
            return new JsonResponse(['error' => 'Demande not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if (!isset($data['status'])) {
            return new JsonResponse(['error' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $demande->setStatus($data['status']);
        $this->entityManager->flush();

        if ($demande->getStatus() === 'Acceptée') {
            // Création de la réservation voyageur
            $reservation = new ReservationVoyageur();
            $reservation->setDateArrivee($demande->getDateArrivee());
            $reservation->setDateDepart($demande->getDateDepart());
            $reservation->setGuestNb($demande->getGuestNb());
            $reservation->setTotalPrice($demande->getTotalPrice());
            $reservation->setProperty($demande->getProperty());
            $reservation->setVoyageurId($demande->getVoyageurId());
            $reservation->setReservationNumber($demande->getReservationNumber());

            $this->entityManager->persist($reservation);

            // Création du paiement
            $payment = new Payment();
            $payment->setDate(new \DateTime());
            $payment->setAmount($demande->getTotalPrice());
            $payment->setMethod('credit_card'); // Remplacez par la méthode réelle
            $payment->setReservation($reservation);

            $this->entityManager->persist($payment);

            $this->entityManager->flush();
        }

        return new JsonResponse(['message' => 'Status updated successfully']);
    }
}
