<?php
// src/Controller/CheckVoyageurController.php

namespace App\Controller;

use App\Entity\CheckVoyageur;
use App\Repository\ReservationVoyageurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CheckVoyageurController extends AbstractController
{
    private $entityManager;
    private $mailer;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/api/checkin', name: 'check_in', methods: ['POST'])]
    public function checkIn(Request $request, ReservationVoyageurRepository $reservationRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $reservationId = $data['reservationId'];

        $reservation = $reservationRepository->find($reservationId);
        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $check = new CheckVoyageur();
        $check->setReservation($reservation);
        $check->setCheckIn(new \DateTime());

        $this->entityManager->persist($check);
        $this->entityManager->flush();

        // Send email to proprietor and voyager
        $this->sendNotification($reservation, 'Check-In');

        return new JsonResponse(['message' => 'Checked in successfully'], JsonResponse::HTTP_OK);
    }

    #[Route('/api/checkout', name: 'check_out', methods: ['POST'])]
    public function checkOut(Request $request, ReservationVoyageurRepository $reservationRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $reservationId = $data['reservationId'];

        $reservation = $reservationRepository->find($reservationId);
        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $check = $this->entityManager->getRepository(CheckVoyageur::class)->findOneBy(['reservation' => $reservationId]);
        if (!$check) {
            return new JsonResponse(['error' => 'Check-in record not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $check->setCheckOut(new \DateTime());
        $this->entityManager->flush();

        // Send email to proprietor and voyager
        $this->sendNotification($reservation, 'Check-Out');

        return new JsonResponse(['message' => 'Checked out successfully'], JsonResponse::HTTP_OK);
    }

    private function sendNotification($reservation, $action)
    {
        $voyageurEmail = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($reservation->getVoyageur()->getEmail())
            ->subject("Votre $action a été enregistré")
            ->text("Votre $action pour la propriété {$reservation->getProperty()->getName()} a été enregistré.");

        $this->mailer->send($voyageurEmail);

        $proprietorEmail = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($reservation->getProperty()->getProprio()->getEmail())
            ->subject("Nouveau $action enregistré")
            ->text("Un $action a été enregistré pour votre propriété {$reservation->getProperty()->getName()}.");

        $this->mailer->send($proprietorEmail);
    }
}
