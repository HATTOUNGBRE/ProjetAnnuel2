<?php
namespace App\Controller;

use App\Entity\DemandePrestation;
use App\Entity\User;
use App\Repository\DemandePrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DemandePrestationController extends AbstractController
{
    #[Route('/api/demande-prestations', name: 'create_demande_prestation', methods: ['POST'])]
    public function createDemande(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titre'], $data['description'], $data['dateDEffet'], $data['dateDeFin'], $data['type'], $data['userId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $demande = new DemandePrestation();
        $demande->setTitre($data['titre']);
        $demande->setDescription($data['description']);
        $demande->setDateDEffet(new \DateTime($data['dateDEffet']));
        $demande->setDateDeFin(new \DateTime($data['dateDeFin']));
        $demande->setType($data['type']);
        $demande->setStatut('en attente');
        $demande->setUser($entityManager->getRepository(User::class)->find($data['userId']));

        $errors = $validator->validate($demande);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($demande);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande de prestation créée avec succès'], 201);
    }

    #[Route('/api/demande-prestations', name: 'get_demande_prestations', methods: ['GET'])]
    public function getDemandePrestations(DemandePrestationRepository $demandePrestationRepository): JsonResponse
    {
        $demandes = $demandePrestationRepository->findPendingDemandes();
        $data = [];

        foreach ($demandes as $demande) {
            $data[] = [
                'id' => $demande->getId(),
                'titre' => $demande->getTitre(),
                'description' => $demande->getDescription(),
                'dateDEffet' => $demande->getDateDEffet()->format('Y-m-d'),
                'dateDeFin' => $demande->getDateDeFin()->format('Y-m-d'),
                'statut' => $demande->getStatut(),
                'user' => [
                    'id' => $demande->getUser()->getId(),
                    'name' => $demande->getUser()->getName(),
                ]
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/demande-prestations/{id}/accept', name: 'accept_demande_prestation', methods: ['POST'])]
    public function acceptDemandePrestation(int $id, EntityManagerInterface $entityManager, DemandePrestationRepository $demandePrestationRepository): JsonResponse
    {
        $demande = $demandePrestationRepository->find($id);

        if (!$demande) {
            return new JsonResponse(['message' => 'Demande de prestation non trouvée'], 404);
        }

        // Create Prestation from DemandePrestation
        $prestation = new Prestation();
        $prestation->setTitre($demande->getTitre());
        $prestation->setDescription($demande->getDescription());
        $prestation->setDateDEffet($demande->getDateDEffet());
        $prestation->setDateDeFin($demande->getDateDeFin());
        $prestation->setType($demande->getType());
        $prestation->setDateDeCreation(new \DateTimeImmutable());
        $prestation->setUser($demande->getUser());

        $entityManager->persist($prestation);

        // Update DemandePrestation statut
        $demande->setStatut('acceptée');

        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande de prestation acceptée'], 200);
    }

    #[Route('/api/demande-prestations/{id}/reject', name: 'reject_demande_prestation', methods: ['POST'])]
    public function rejectDemandePrestation(int $id, EntityManagerInterface $entityManager, DemandePrestationRepository $demandePrestationRepository): JsonResponse
    {
        $demande = $demandePrestationRepository->find($id);

        if (!$demande) {
            return new JsonResponse(['message' => 'Demande de prestation non trouvée'], 404);
        }

        // Update DemandePrestation statut
        $demande->setStatut('rejetée');

        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande de prestation rejetée'], 200);
    }
}
