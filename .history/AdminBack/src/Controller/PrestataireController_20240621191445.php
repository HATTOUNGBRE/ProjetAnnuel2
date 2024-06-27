<?php

namespace App\Controller;

use App\Entity\Prestataire;
use App\Entity\User;
use App\Repository\PrestataireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PrestataireController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/prestataires', name: 'create_prestataire', methods: ['POST'])]
    public function createPrestataire(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userId = $data['user_id'] ?? null; // Retrieve user_id from the request data
        $type = $data['type'] ?? null;
        $tarif = $data['tarif'] ?? null;

        if (!$userId || !$type || !$tarif) {
            return new JsonResponse(['error' => 'Missing data'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $prestataire = new Prestataire();
        $prestataire->setUser($user);
        $prestataire->setType($type);
        $prestataire->setTarif($tarif);
        $prestataire->setVerified(false); // Default value, can be changed later

        $this->entityManager->persist($prestataire);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Prestataire created successfully'], Response::HTTP_CREATED);
    }

    #[Route('/prestataires/{userId}', name: 'get_prestataire', methods: ['GET'])]
    public function getPrestataire(int $userId, PrestataireRepository $prestataireRepository): JsonResponse
    {
        $prestataire = $prestataireRepository->findOneBy(['user' => $userId]);

        if (!$prestataire) {
            return new JsonResponse(['error' => 'Prestataire not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $prestataire->getId(),
            'type' => $prestataire->getType(),
            'tarif' => $prestataire->getTarif(),
            'verified' => $prestataire->isVerified(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    // Autres méthodes du contrôleur pour la gestion des prestataires
}
