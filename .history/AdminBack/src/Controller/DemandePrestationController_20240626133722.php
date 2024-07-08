<?
namespace App\Controller;

use App\Entity\DemandePrestation;
use App\Repository\DemandePrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DemandePrestationController extends AbstractController
{
    #[Route('/api/demande-prestations', name: 'create_demande_prestation', methods: ['POST'])]
    public function createDemandePrestation(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titre'], $data['description'], $data['dateDEffet'], $data['type'], $data['userId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $demande = new DemandePrestation();
        $demande->setTitre($data['titre']);
        $demande->setDescription($data['description']);
        $demande->setDateDEffet(new \DateTime($data['dateDEffet']));
        $demande->setType($data['type']);
        $demande->setStatut('en attente');
        $demande->setUser($entityManager->getRepository(User::class)->find($data['userId']));

        $errors = $validator->validate($demande);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => (string) $errors], 400);
        }

        $entityManager->persist($demande);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande de prestation créée avec succès'], 201);
    }

    #[Route('/api/demande-prestations', name: 'get_demande_prestations', methods: ['GET'])]
    public function getDemandePrestations(DemandePrestationRepository $demandePrestationRepository): JsonResponse
    {
        $demandes = $demandePrestationRepository->findBy(['statut' => 'en attente']);
        $data = [];

        foreach ($demandes as $demande) {
            $data[] = [
                'id' => $demande->getId(),
                'titre' => $demande->getTitre(),
                'description' => $demande->getDescription(),
                'dateDEffet' => $demande->getDateDEffet()->format('Y-m-d H:i:s'),
                'type' => $demande->getType(),
                'statut' => $demande->getStatut(),
                'user' => [
                    'id' => $demande->getUser()->getId(),
                    'name' => $demande->getUser()->getName(),
                ]
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
