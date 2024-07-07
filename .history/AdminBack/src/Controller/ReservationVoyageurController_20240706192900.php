<?php
// src/Controller/ReservationVoyageurController.php

namespace App\Controller;

use App\Entity\ReservationVoyageur;
use App\Repository\PropertyRepository;
use App\Repository\ReservationVoyageurRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/api/reservation', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(
        Request $request,
        EntityManagerInterface $entityManager,
        PropertyRepository $propertyRepository,
        UserRepository $userRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $property = $propertyRepository->find($data['propertyId']);
        $user = $userRepository->find($data['userId']);

        if (!$property || !$user) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid property or user.'], 400);
        }

        $reservation = new Reservation();
        $reservation->setDateArrivee(new \DateTime($data['dateArrivee']));
        $reservation->setDateDepart(new \DateTime($data['dateDepart']));
        $reservation->setGuestNb($data['guestNb']);
        $reservation->setProperty($property);
        $reservation->setUser($user);

        $entityManager->persist($reservation);
        $entityManager->flush();

        Stripe::setApiKey('your-stripe-secret-key');

        $paymentIntent = PaymentIntent::create([
            'amount' => $data['amount'],
            'currency' => 'eur',
            'payment_method' => $data['paymentMethodId'],
            'confirmation_method' => 'manual',
            'confirm' => true,
        ]);

        // Vérifier le statut du paiement
        if ($paymentIntent->status === 'requires_action' &&
            $paymentIntent->next_action->type === 'use_stripe_sdk') {
            return new JsonResponse([
                'requiresAction' => true,
                'paymentIntentClientSecret' => $paymentIntent->client_secret,
            ]);
        } elseif ($paymentIntent->status === 'succeeded') {
            // Enregistrer le paiement
            $payment = new Payment();
            $payment->setDate(new \DateTime());
            $payment->setAmount($data['amount']);
            $payment->setMethod($data['paymentMethod']);
            $payment->setReservation($reservation);

            $entityManager->persist($payment);
            $entityManager->flush();

            return new JsonResponse(['success' => true]);
        } else {
            return new JsonResponse(['success' => false, 'error' => 'Invalid PaymentIntent status.']);
        }
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
}
