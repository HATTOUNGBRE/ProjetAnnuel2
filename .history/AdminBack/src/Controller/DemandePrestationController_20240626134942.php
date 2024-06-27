<?
namespace App\Controller;

use App\Entity\DemandePrestation;
use App\Entity\Prestation;
use App\Repository\DemandePrestationRepository;
use App\Repository\PrestationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DemandePrestationController extends AbstractController
{
    #[Route('/api/demande-prestations', name: 'create_demande_prestation', methods: ['POST'])]
    public function createDemandePrestation(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titre'], $data['description'], $data['dateDEffet'], $data['type'], $data['userId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $user = $userRepository->find($data['userId']);
        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], 404);
        }

        $demande = new DemandePrestation();
        $demande->setTitre($data['titre']);
        $demande->setDescription($data['description']);
        $demande->setDateDEffet(new \DateTime($data['dateDEffet']));
        $demande->setType($data['type']);
        $demande->setStatut('en attente');
        $demande->setUser($user);

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

    #[Route('/api/demande-prestations/{id}/accept', name: 'accept_demande_prestation', methods: ['POST'])]
    public function acceptDemandePrestation(int $id, Request $request, EntityManagerInterface $entityManager, DemandePrestationRepository $demandePrestationRepository, ValidatorInterface $validator, UserRepository $userRepository): JsonResponse
    {
        $demande = $demandePrestationRepository->find($id);
        if (!$demande) {
            return new JsonResponse(['message' => 'Demande de prestation non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['dateDeFin'], $data['prestataireId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $prestataire = $userRepository->find($data['prestataireId']);
        if (!$prestataire) {
            return new JsonResponse(['message' => 'Prestataire non trouvé'], 404);
        }

        $prestation = new Prestation();
        $prestation->setTitre($demande->getTitre());
        $prestation->setDescription($demande->getDescription());
        $prestation->setDateDEffet($demande->getDateDEffet());
        $prestation->setDateDeFin(new \DateTime($data['dateDeFin']));
        $prestation->setType($demande->getType());
        $prestation->setDateDeCreation(new \DateTimeImmutable());
        $prestation->setUser($prestataire);

        $errors = $validator->validate($prestation);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => (string) $errors], 400);
        }

        $entityManager->persist($prestation);

        $demande->setStatut('acceptée');
        $entityManager->flush();

        return new JsonResponse(['message' => 'Demande de prestation acceptée et prestation créée avec succès'], 201);
    }
    #[Route('/api/user-demandes-prestations/{userId}', name: 'user_demandes_prestations', methods: ['GET'])]
    public function getUserDemandesPrestations(int $userId, DemandePrestationRepository $demandePrestationRepository): JsonResponse
    {
        $demandes = $demandePrestationRepository->findBy(['user' => $userId]);
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
