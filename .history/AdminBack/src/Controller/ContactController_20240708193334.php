<?php
// src/Controller/ContactController.php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Utils\ReservationNumberGenerator;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $ticket = new Ticket();
        $ticket->setName($data['name']);
        $ticket->setSurname($data['surname']);
        $ticket->setEmail($data['email']);
        $ticket->setRole($data['role']);
        $ticket->setQuestion($data['question']);
        $ticket->setMessage($data['message']);
        $ticket->setTicketNumber(ReservationNumberGenerator::generate());

        $entityManager->persist($ticket);
        $entityManager->flush();

        // Envoi de l'email
        $email = (new Email())
            ->from('noreply@yourdomain.com')
            ->to('hello.teampcs@outlook.com')
            ->subject('Nouvelle demande de contact')
            ->text("Vous avez reçu une nouvelle demande de contact.\n\nNom: {$ticket->getName()}\nPrénom: {$ticket->getSurname()}\nEmail: {$ticket->getEmail()}\nRôle: {$ticket->getRole()}\nQuestion: {$ticket->getQuestion()}\nMessage: {$ticket->getMessage()}\nNuméro de ticket: {$ticket->getTicketNumber()}");

        $mailer->send($email);

        return new JsonResponse(['message' => 'Votre message a été envoyé avec succès.'], JsonResponse::HTTP_OK);
    }
}
