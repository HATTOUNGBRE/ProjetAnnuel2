<?php
// src/Controller/ContactController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ticket;
use App\Utils\ReservationNumberGenerator;

class ContactController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request, MailerInterface $mailer, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $ticket = new Ticket();
        $ticket->setName($data['name']);
        $ticket->setSurname($data['surname']);
        $ticket->setEmail($data['email']);
        $ticket->setRole($data['role']);
        $ticket->setQuestion($data['question']);
        $ticket->setStatus('Ouvert');
        $ticket->setMessage($data['message']);
        $ticket->setTicketNumber(ReservationNumberGenerator::generate());

        $this->entityManager->persist($ticket);
        $this->entityManager->flush();

        $logger->info('Preparing email to send', ['email' => $data['email'], 'message' => $data['message']]);

        $userEmail = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($data['email'])
            ->subject('Confirmation de votre demande')
            ->text("Nous avons bien reçu votre demande : \"{$data['message']}\". Notre équipe y répondra bientôt.\n\nNuméro de ticket : {$ticket->getTicketNumber()}");

        $mailer->send($userEmail);

        $logger->info('Email sent to user', ['email' => $data['email']]);

        // Envoi de l'email à l'équipe support
        $supportEmail = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to('hello.teampcs@outlook.com')
            ->subject('Nouvelle demande de contact')
            ->text("Vous avez reçu une nouvelle demande de contact.\n\nNom: {$data['name']}\nPrénom: {$data['surname']}\nEmail: {$data['email']}\nRôle: {$data['role']}\nQuestion: {$data['question']}\nMessage: {$data['message']}\nNuméro de ticket : {$ticket->getTicketNumber()}");

        $mailer->send($supportEmail);

        $logger->info('Email sent to support', ['email' => 'hello.teampcs@outlook.com']);

        return new JsonResponse(['message' => 'Emails sent successfully'], JsonResponse::HTTP_CREATED);
    }
}
