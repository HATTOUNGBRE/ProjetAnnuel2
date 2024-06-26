<?php

namespace App\Controller;

use App\Entity\Prestataire;
use App\Entity\User;
use App\Entity\Disponibilite;
use App\Repository\PrestataireRepository;
use App\Repository\UserRepository;
use App\Repository\DisponibiliteRepository;
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
        $prestataire->setVerified(false); // Initial state is unverified

        $this->entityManager->persist($prestataire);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Prestataire created successfully'], Response::HTTP_CREATED);
    }

    #[Route('/prestataires/{userId}', name: 'get_prestataire', methods: ['GET'])]
    public function getPrestataires(int $userId, PrestataireRepository $prestataireRepository): JsonResponse
    {
        $prestataires = $prestataireRepository->findBy(['user' => $userId]);

        if (!$prestataires) {
            return new JsonResponse(['error' => 'Prestataires not found'], Response::HTTP_NOT_FOUND);
        }

        $data = [];
        foreach ($prestataires as $prestataire) {
            $data[] = [
                'id' => $prestataire->getId(),
                'type' => $prestataire->getType(),
                'tarif' => $prestataire->getTarif(),
                'verified' => $prestataire->isVerified(),
                'habilitations' => $prestataire->getHabilitations(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/prestataires/submit', name: 'submit_prestataire', methods: ['POST'])]
    public function submit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $prestataire = new Prestataire();
        $prestataire->setType($data['type']);
        $prestataire->setTarif($data['tarif']);
        $prestataire->setHabilitations($data['habilitations']);
        $prestataire->setVerified(false);
        $prestataire->setUser($this->getUser());

        $this->entityManager->persist($prestataire);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Prestataire submitted successfully'], Response::HTTP_CREATED);
    }

    #[Route('/prestataires/validate/{id}', name: 'validate_prestataire', methods: ['PUT'])]
    public function validate(int $id): JsonResponse
    {
        $prestataire = $this->entityManager->getRepository(Prestataire::class)->find($id);

        if (!$prestataire) {
            return new JsonResponse(['error' => 'Prestataire not found'], Response::HTTP_NOT_FOUND);
        }

        $prestataire->setVerified(true);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Prestataire validated successfully'], Response::HTTP_OK);
    }

    #[Route('/disponibilites/{prestataireId}', name: 'get_disponibilites', methods: ['GET'])]
    public function getDisponibilites(int $prestataireId, DisponibiliteRepository $disponibiliteRepository): JsonResponse
    {
        $disponibilites = $disponibiliteRepository->findBy(['prestataire' => $prestataireId]);

        $data = [];
        foreach ($disponibilites as $disponibilite) {
            $data[] = [
                'id' => $disponibilite->getId(),
                'start' => $disponibilite->getStart()->format('Y-m-d H:i:s'),
                'end' => $disponibilite->getEnd()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/disponibilites/add', name: 'add_disponibilite', methods: ['POST'])]
    public function addDisponibilite(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $prestataire = $this->entityManager->getRepository(Prestataire::class)->find($data['prestataire_id']);

        if (!$prestataire) {
            return new JsonResponse(['error' => 'Prestataire not found'], Response::HTTP_NOT_FOUND);
        }

        $disponibilite = new Disponibilite();
        $disponibilite->setStart(new \DateTime($data['start']));
        $disponibilite->setEnd(new \DateTime($data['end']));
        $disponibilite->setPrestataire($prestataire);

        $this->entityManager->persist($disponibilite);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Disponibilite added successfully'], Response::HTTP_CREATED);
    }
}
