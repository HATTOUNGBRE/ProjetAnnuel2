<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $mailer;
    private $router;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, UrlGeneratorInterface $router, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
        $this->router = $router;
        $this->logger = $logger;
    }

    #[Route('/api/request-reset-password', name: 'request_reset_password', methods: ['POST'])]
    public function requestResetPassword(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';

        if (empty($email)) {
            return new JsonResponse(['message' => 'Email is required'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Generate reset token
        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);
        $user->setTokenExpiration(new \DateTime('+1 hour'));
        $this->entityManager->flush();

        // Send reset email
        $resetUrl = 'http://localhost:5173/reset-password/' . $resetToken;
        $emailMessage = (new Email())
            ->from('hello.teampcs@outlook.com')
            ->to($user->getEmail())
            ->subject('Password Reset Request')
            ->html(sprintf('To reset your password, please click <a href="%s">here</a>.', $resetUrl));

        try {
            $this->mailer->send($emailMessage);
            // Log email sending
            $this->logger->channel('mailer')->info('Password reset email sent', [
                'email' => $user->getEmail(),
                'resetUrl' => $resetUrl,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => 'Failed to send email: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['message' => 'Reset email sent'], Response::HTTP_OK);
    }

    #[Route('/api/reset-password/{token}', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, string $token): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $password = $data['password'] ?? '';

        if (empty($password)) {
            return new JsonResponse(['message' => 'Password is required'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user || $user->getTokenExpiration() < new \DateTime()) {
            return new JsonResponse(['message' => 'Invalid or expired token'], Response::HTTP_BAD_REQUEST);
        }

        // Update password
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        $user->setResetToken(null);
        $user->setTokenExpiration(null);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Password reset successfully'], Response::HTTP_OK);
    }
}
