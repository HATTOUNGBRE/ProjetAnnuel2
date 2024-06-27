<?php
// src/Controller/DemandePrestationController.php

namespace App\Controller;

use App\Entity\DemandePrestation;
use App\Entity\Prestation;
use App\Repository\DemandePrestationRepository;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DemandePrestationController extends AbstractController
{
    #[Route('/api/demande-prestations', name: 'demande_prestations', methods: ['GET'])]
    public function index(DemandePrestationRepository $demandePrestationRepository): JsonResponse
    {
        $demandes = $demandePrestationRepository->findAll();
        return $this->json($demandes, 200, [], ['groups' => 'demande_prestation:read']);
    }

    #[Route('/api/demande-prestations/{id}/accept', name: 'accept_demande_prestation', methods: ['POST'])]
    public function accept(int $id, DemandePrestationRepository $demandePrestationRepository, EntityManagerInterface $entityManager, PrestationRepository $prestationRepository): JsonResponse
    {
        $demande = $demandePrestationRepository->find($id);
        if (!$demande) {
            return new JsonResponse(['message' => 'Demande not found'], 404);
        }

        $prestation = new Prestation();
        $prestation->setTitre($demande->getTitre());
        $prestation->setDescription($demande->getDescription());
        $prestation->setDateDEffet($demande->getDateDEffet());
        $prestation->setDateDeCreation(new \DateTimeImmutable());
        $prestation->setType($demande->getType());
        $prestation->setUser($demande->getUser());

        $demande->setStatut('acceptée');

        $entityManager->persist($prestation);
        $entityManager->persist($demande);
        $entityManager->flush();

        $this->logAction('accept', $prestation->getId(), $prestation->getUser()->getId());

        return new JsonResponse(['message' => 'Demande acceptée et prestation créée'], 200);
    }

    #[Route('/api/demande-prestations/{id}/reject', name: 'reject_demande_prestation', methods: ['POST'])]
    public function reject(int $id, DemandePrestationRepository $demandePrestationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $demande = $demandePrestationRepository->find($id);
        if (!$demande) {
            return new JsonResponse(['message' => 'Demande not found'], 404);
        }

        $demande->setStatut('refusée');

        $entityManager->persist($demande);
        $entityManager->flush();

        $this->logAction('reject', $demande->getId(), $demande->getUser()->getId());

        return new JsonResponse(['message' => 'Demande refusée'], 200);
    }

    #[Route('/api/prestations/{id}/status', name: 'update_prestation_status', methods: ['PUT'])]
    public function updateStatus(int $id, Request $request, PrestationRepository $prestationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $prestation = $prestationRepository->find($id);
        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['status']) && $data['status'] === 'completed') {
            $prestation->setStatut('Terminée');
            $entityManager->persist($prestation);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Prestation marked as completed'], 200);
        }

        return new JsonResponse(['message' => 'Invalid status'], 400);
    }

    private function logAction($action, $prestationId, $userId)
    {
        $filesystem = new Filesystem();
        $logMessage = sprintf("User %d has %s prestation %d", $userId, $action, $prestationId);
        try {
            $filesystem->appendToFile($this->getParameter('kernel.project_dir') . '/var/log/prestation_actions.log', $logMessage . PHP_EOL);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while writing to log file at " . $exception->getPath();
        }
    }
}
