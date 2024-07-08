<?php
// src/Controller/ContactController.php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

class ContactController extends AbstractController
{
    #[Route('/api/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $email = (new Email())
            ->from('noreply@yourdomain.com')
            ->to($data['email'])
            ->subject('Confirmation de votre demande')
            ->text("Nous avons bien reçu votre demande : \"{$data['message']}\". Notre équipe y répondra bientôt.");

        $bus->dispatch(new SendEmailMessage($email));

        return new JsonResponse(['message' => 'Email queued for sending'], JsonResponse::HTTP_CREATED);
    }
}
