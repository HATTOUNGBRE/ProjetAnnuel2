<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Entity\User;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PrestationController extends AbstractController
{
    #[Route('/api/prestations', name: 'create_prestation', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $prestation = new Prestation();
        $prestation->setTitre($data['titre']);
        $prestation->setDescription($data['description']);
        $prestation->setDateDeCreation(new \DateTimeImmutable());
        $prestation->setDateDEffet(new \DateTime($data['dateDEffet']));
        $prestation->setDateDeFin(new \DateTime($data['dateDeFin']));
        $prestation->setStatut('En attente');
        $prestation->setActive(true);
        $prestation->setProprietaire($this->getUser());

        $errors = $validator->validate($prestation);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => (string) $errors], 400);
        }

        $entityManager->persist($prestation);
        $entityManager->flush();

        return new JsonResponse($serializer->normalize($prestation, 'json', ['groups' => 'prestation:read']), 201);
    }

    #[Route('/api/prestations/{id}', name: 'update_prestation', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        PrestationRepository $prestationRepository,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ): JsonResponse {
        $prestation = $prestationRepository->find($id);
        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée.'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $prestation->setTitre($data['titre'] ?? $prestation->getTitre());
        $prestation->setDescription($data['description'] ?? $prestation->getDescription());
        $prestation->setDateDEffet(new \DateTime($data['dateDEffet'] ?? $prestation->getDateDEffet()->format('Y-m-d H:i:s')));
        $prestation->setDateDeFin(new \DateTime($data['dateDeFin'] ?? $prestation->getDateDeFin()->format('Y-m-d H:i:s')));
        $prestation->setStatut($data['statut'] ?? $prestation->getStatut());
        $prestation->setActive($data['active'] ?? $prestation->isActive());

        $errors = $validator->validate($prestation);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => (string) $errors], 400);
        }

        $entityManager->flush();

        return new JsonResponse($serializer->normalize($prestation, 'json', ['groups' => 'prestation:read']), 200);
    }

    #[Route('/api/prestations/{id}', name: 'delete_prestation', methods: ['DELETE'])]
    public function delete(
        int $id,
        PrestationRepository $prestationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $prestation = $prestationRepository->find($id);
        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée.'], 404);
        }

        $entityManager->remove($prestation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation supprimée avec succès.'], 200);
    }

    #[Route('/api/prestations', name: 'get_prestations', methods: ['GET'])]
    public function getAll(PrestationRepository $prestationRepository, SerializerInterface $serializer): JsonResponse
    {
        $prestations = $prestationRepository->findAll();
        return new JsonResponse($serializer->normalize($prestations, 'json', ['groups' => 'prestation:read']), 200);
    }
}
