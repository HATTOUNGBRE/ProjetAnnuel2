<?php

namespace App\Controller;

use App\Entity\CheckVoyageur;
use App\Repository\DemandeReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class CheckVoyageurController extends AbstractController
{
    private $entityManager;
    private $mailer;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    #[Route('/api/checkin', name: 'check_in', methods: ['POST'])]
    public function checkIn(Request $request, DemandeReservationRepository $demandeReservationRepository): JsonResponse
    {
        $this->logger->info('checkIn endpoint called');

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            $this->logger->error('Invalid JSON');
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['demandeId'])) {
            $this->logger->error('Missing demandeId');
            return new JsonResponse(['error' => 'Missing demandeId'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $demandeId = $data['demandeId'];
        $this->logger->info('Demande ID received', ['demandeId' => $demandeId]);

        $demande = $demandeReservationRepository->find($demandeId);

        if (!$demande) {
            $this->logger->error('DemandeReservation not found', ['demandeId' => $demandeId]);
            return new JsonResponse(['error' => 'DemandeReservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $check = new CheckVoyageur();
        $check->setDemandeReservation($demande);
        $check->setCheckIn(new \DateTime());

        $this->entityManager->persist($check);
        $this->entityManager->flush();

        // Send email to proprietor and voyager
        $this->sendNotification($demande, 'Check-In');

        $this->logger->info('Checked in successfully', ['demandeId' => $demandeId]);
        return new JsonResponse(['message' => 'Checked in successfully'], JsonResponse::HTTP_OK);
    }

    private function sendNotification($demande, $action)
    {
        $proprietorEmail = $demande->getProperty()->getProprio()->getEmail();
        $voyagerEmail = $demande->getVoyageur()->getEmail();
        $propertyName = $demande->getProperty()->getName();

        $this->logger->info('Sending notification emails', [
            'proprietorEmail' => $proprietorEmail,
            'voyagerEmail' => $voyagerEmail,
            'propertyName' => $propertyName,
            'action' => $action
        ]);

        // Email to proprietor
        $proprietorEmailMessage = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($proprietorEmail)
            ->subject("$action Confirmation")
            ->text("The voyager has completed the action: $action at the property: $propertyName.");

        // Email to voyager
        $voyagerEmailMessage = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($voyagerEmail)
            ->subject("$action Confirmation")
            ->text("You have successfully completed the action: $action at the property: $propertyName.");

        $this->mailer->send($proprietorEmailMessage);
        $this->mailer->send($voyagerEmailMessage);

        $this->logger->info('Notification emails sent');
    }

    #[Route('/api/checkout', name: 'check_out', methods: ['POST'])]
    public function checkOut(Request $request, DemandeReservationRepository $demandeReservationRepository): JsonResponse
    {
        $this->logger->info('checkOut endpoint called');

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            $this->logger->error('Invalid JSON');
            return new JsonResponse(['error' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['demandeId'])) {
            $this->logger->error('Missing demandeId');
            return new JsonResponse(['error' => 'Missing demandeId'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $demandeId = $data['demandeId'];
        $this->logger->info('Demande ID received', ['demandeId' => $demandeId]);

        $demande = $demandeReservationRepository->find($demandeId);

        if (!$demande) {
            $this->logger->error('DemandeReservation not found', ['demandeId' => $demandeId]);
            return new JsonResponse(['error' => 'DemandeReservation not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $check = $this->entityManager->getRepository(CheckVoyageur::class)->findOneBy(['demandeReservation' => $demandeId]);

        if (!$check) {
            $this->logger->error('Check-in record not found', ['demandeId' => $demandeId]);
            return new JsonResponse(['error' => 'Check-in record not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $check->setCheckOut(new \DateTime());
        $this->entityManager->flush();

        // Send email to proprietor and voyager
        $this->sendNotification($demande, 'Check-Out');

        $this->logger->info('Checked out successfully', ['demandeId' => $demandeId]);
        return new JsonResponse(['message' => 'Checked out successfully'], JsonResponse::HTTP_OK);
    }
}
