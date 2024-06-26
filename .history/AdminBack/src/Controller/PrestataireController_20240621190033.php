<?php

namespace App\Controller;

use App\Entity\Prestataire;
use App\Entity\User;

use App\Repository\PrestataireRepository;
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
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
    #[Route('/prestataires', name: 'create_prestataire', methods: ['POST'])]
    public function createPrestataire(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['type']) || empty($data['tarif']) || empty($data['user_id'])) {
            return new JsonResponse(['error' => 'Invalid input'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->find($data['user_id']);

        if (!$user || $user->getCategoryUser()->getId() !== 3) {
            return new JsonResponse(['error' => 'User not found or not a valid prestataire'], Response::HTTP_BAD_REQUEST);
        }

        $prestataire = new Prestataire();
        $prestataire->setUser($user);
        $prestataire->setType($data['type']);
        $prestataire->setTarif($data['tarif']);
        // $prestataire->setVerified(false); // Removed as per your instructions

        $this->entityManager->persist($prestataire);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Prestataire créé avec succès'], Response::HTTP_CREATED);
    }

    // Autres méthodes du contrôleur pour la gestion des prestataires
}
