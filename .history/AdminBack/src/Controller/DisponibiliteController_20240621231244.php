<?php
// src/Controller/DisponibiliteController.php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Entity\Prestataire;
use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/disponibilites')]
class DisponibiliteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/{prestataireId}', name: 'get_disponibilites', methods: ['GET'])]
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

    #[Route('/add', name: 'add_disponibilite', methods: ['POST'])]
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
