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
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $logger;
    private $mailer;
    private $router;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, LoggerInterface $logger, MailerInterface $mailer, UrlGeneratorInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->router = $router;
    }

    #[Route('/api/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $this->logger->info('Received login request');

        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $role = $data['role'] ?? '';

        $this->logger->debug('Email:', ['email' => $email]);
        $this->logger->debug('Role:', ['role' => $role]);

        if (empty($email) || empty($password) || empty($role)) {
            $this->logger->error('Missing required data');
            return new JsonResponse(['message' => 'Tous les champs sont requis'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            $this->logger->error('Invalid credentials');
            return new JsonResponse(['message' => 'Identifiants invalides'], Response::HTTP_UNAUTHORIZED);
        }

        $categoryId = $user->getCategoryUser()->getId();
        if (
            ($role === 'voyageur' && $categoryId != 2) ||
            ($role === 'proprietaire' && $categoryId != 1) ||
            ($role === 'prestataire' && $categoryId != 3)
        ) {
            $this->logger->error('Role does not match user category');
            return new JsonResponse(['message' => 'Le rôle ne correspond pas à l\'utilisateur'], Response::HTTP_FORBIDDEN);
        }

        $this->logger->info('Login successful');
        $token = 'generated_jwt_token';

        $route = match ($role) {
            'voyageur' => '/components/dashboard/voyageur',
            'proprietaire' => '/components/dashboard/proprietaire',
            'prestataire' => '/components/dashboard/prestataire',
            default => 'login?{$role}',
        };

        $this->logger->info('Returning user data', [
            'userId' => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'role' => $role,
            'redirect' => $route,
            'category' => $categoryId,
            'token' => $token
        ]);

        return new JsonResponse([
            'redirect' => $route,
            'categoryType' => $role,
            'category' => $categoryId,
            'userId' => $user->getId(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'token' => $token
        ], Response::HTTP_OK);
    }

   
}
