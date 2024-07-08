<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Entity\User;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PrestationController extends AbstractController
{
    #[Route('/api/prestations', name: 'create_prestation', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titre'], $data['description'], $data['dateDEffet'], $data['type'], $data['userId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $prestation = new Prestation();
        $prestation->setTitre($data['titre']);
        $prestation->setDescription($data['description']);
        $prestation->setDateDEffet(new \DateTime($data['dateDEffet']));
        $prestation->setStatut('en attente');
        $prestation->setActive(true);
        $prestation->setDateDeCreation(new \DateTimeImmutable());
        $prestation->setType($data['type']);
        $prestation->setUser($entityManager->getRepository(User::class)->find($data['userId']));

        $errors = $validator->validate($prestation);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($prestation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation créée avec succès'], 201);
    }

    #[Route('/api/user-prestations/{userId}', name: 'user_prestations', methods: ['GET'])]
    public function getUserPrestations(int $userId, PrestationRepository $prestationRepository): JsonResponse
    {
        $prestations = $prestationRepository->findBy(['user' => $userId]);
        $data = [];

        foreach ($prestations as $prestation) {
            $data[] = [
                'id' => $prestation->getId(),
                'titre' => $prestation->getTitre(),
                'description' => $prestation->getDescription(),
                'dateDEffet' => $prestation->getDateDEffet()->format('Y-m-d'),
                'statut' => $prestation->getStatut(),
                'user' => [
                    'id' => $prestation->getUser()->getId(),
                    'name' => $prestation->getUser()->getName(),
                ]
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/prestations/unassigned', name: 'unassigned_prestations', methods: ['GET'])]
    public function getUnassignedPrestations(PrestationRepository $prestationRepository): JsonResponse
    {
        $prestations = $prestationRepository->findBy(['statut' => 'en attente']);
        $data = [];

        foreach ($prestations as $prestation) {
            $data[] = [
                'id' => $prestation->getId(),
                'titre' => $prestation->getTitre(),
                'description' => $prestation->getDescription(),
                'dateDEffet' => $prestation->getDateDEffet()->format('Y-m-d'),
                'statut' => $prestation->getStatut(),
                'user' => [
                    'id' => $prestation->getUser()->getId(),
                    'name' => $prestation->getUser()->getName(),
                ]
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/prestations/{id}/accept', name: 'accept_prestation', methods: ['POST'])]
    public function acceptPrestation(int $id, EntityManagerInterface $entityManager, PrestationRepository $prestationRepository): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée'], 404);
        }

        $prestation->setStatut('Confirmée');
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation acceptée avec succès'], 200);
    }

    #[Route('/api/prestations/{id}/reject', name: 'reject_prestation', methods: ['POST'])]
    public function rejectPrestation(int $id, EntityManagerInterface $entityManager, PrestationRepository $prestationRepository): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée'], 404);
        }

        $prestation->setStatut('Rejetée');
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation rejetée avec succès'], 200);
    }
}
