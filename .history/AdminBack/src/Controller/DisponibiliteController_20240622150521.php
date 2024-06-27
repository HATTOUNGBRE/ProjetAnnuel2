<?php

namespace App\Controller;

use App\Entity\Disponibilite;
use App\Repository\DisponibiliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function getDisponibilites($prestataireId, DisponibiliteRepository $disponibiliteRepository): JsonResponse
    {
        // Convert prestataireId to int if it's a string
        $prestataireId = (int) $prestataireId;

        $disponibilites = $disponibiliteRepository->findBy(['prestataire' => $prestataireId]);

        $data = [];
        foreach ($disponibilites as $disponibilite) {
            $data[] = [
                'id' => $disponibilite->getId(),
                'start' => $disponibilite->getStart()->format('Y-m-d H:i:s'),
                'end' => $disponibilite->getEnd()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/add', name: 'add_disponibilite', methods: ['POST'])]
    public function addDisponibilite(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $prestataireId = (int) $data['id'];

        $prestataire = $this->entityManager->getRepository(Prestataire::class)->find($id);

        if (!$prestataire) {
            return new JsonResponse(['error' => 'Prestataire not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $disponibilite = new Disponibilite();
        $disponibilite->setStart(new \DateTime($data['start']));
        $disponibilite->setEnd(new \DateTime($data['end']));
        $disponibilite->setPrestataire($prestataire);

        $this->entityManager->persist($disponibilite);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Disponibilite added successfully'], JsonResponse::HTTP_CREATED);
    }
}
