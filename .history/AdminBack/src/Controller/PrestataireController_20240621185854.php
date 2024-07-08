<?php

namespace App\Controller;

use App\Entity\Prestataire;
use App\Repository\PrestataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    // Autres méthodes du contrôleur pour la gestion des prestataires
}
