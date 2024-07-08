<?
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


    #[Route('/api/demande-prestations/{id}/accept', name: 'accept_demande_prestation', methods: ['POST'])]
public function acceptDemandePrestation(int $id, EntityManagerInterface $entityManager): JsonResponse
{
    $demandePrestation = $entityManager->getRepository(DemandePrestation::class)->find($id);
    if (!$demandePrestation) {
        return new JsonResponse(['message' => 'Demande non trouvée.'], 404);
    }

    // Create a new Prestation
    $prestation = new Prestation();
    $prestation->setTitre($demandePrestation->getTitre());
    $prestation->setDescription($demandePrestation->getDescription());
    $prestation->setDateDEffet($demandePrestation->getDateDEffet());
    $dateDeFin = (clone $demandePrestation->getDateDEffet())->modify('+1 day');
    $prestation->setDateDeFin($dateDeFin);
    $prestation->setType($demandePrestation->getType());
    $prestation->setUser($this->getUser());
    $prestation->setDateDeCreation(new \DateTimeImmutable());

    // Update the demande status
    $demandePrestation->setStatut('acceptée');

    $entityManager->persist($prestation);
    $entityManager->persist($demandePrestation);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Demande acceptée et prestation créée.'], 200);
}

#[Route('/api/prestations/{id}/status', name: 'update_prestation_status', methods: ['PUT'])]
public function updatePrestationStatus(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $prestation = $entityManager->getRepository(Prestation::class)->find($id);
    if (!$prestation) {
        return new JsonResponse(['message' => 'Prestation non trouvée.'], 404);
    }

    $data = json_decode($request->getContent(), true);
    if (isset($data['status']) && $data['status'] === 'completed') {
        $prestation->setStatut('Terminée');
        $entityManager->persist($prestation);
        $entityManager->flush();
    }

    return new JsonResponse(['message' => 'Statut de la prestation mis à jour.'], 200);
}
    #[Route('/api/prestations', name: 'get_prestations', methods: ['GET'])]
    public function getPrestations(PrestationRepository $prestationRepository): JsonResponse
    {
        $prestations = $prestationRepository->findAll();
        $data = [];

        foreach ($prestations as $prestation) {
            $data[] = [
                'id' => $prestation->getId(),
                'titre' => $prestation->getTitre(),
                'description' => $prestation->getDescription(),
                'dateDEffet' => $prestation->getDateDEffet()->format('Y-m-d'),
                'dateDeFin' => $prestation->getDateDeFin()->format('Y-m-d'),
                'type' => $prestation->getType(),
                'dateDeCreation' => $prestation->getDateDeCreation()->format('Y-m-d'),
                'user' => [
                    'id' => $prestation->getUser()->getId(),
                    'name' => $prestation->getUser()->getName(),
                ]
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/prestations/{id}', name: 'get_prestation', methods: ['GET'])]
    public function getPrestation(int $id, PrestationRepository $prestationRepository): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée'], 404);
        }

        $data = [
            'id' => $prestation->getId(),
            'titre' => $prestation->getTitre(),
            'description' => $prestation->getDescription(),
            'dateDEffet' => $prestation->getDateDEffet()->format('Y-m-d'),
            'dateDeFin' => $prestation->getDateDeFin()->format('Y-m-d'),
            'type' => $prestation->getType(),
            'dateDeCreation' => $prestation->getDateDeCreation()->format('Y-m-d'),
            'user' => [
                'id' => $prestation->getUser()->getId(),
                'name' => $prestation->getUser()->getName(),
            ]
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/prestations', name: 'create_prestation', methods: ['POST'])]
    public function createPrestation(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['titre'], $data['description'], $data['dateDEffet'], $data['dateDeFin'], $data['type'], $data['userId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $prestation = new Prestation();
        $prestation->setTitre($data['titre']);
        $prestation->setDescription($data['description']);
        $prestation->setDateDEffet(new \DateTime($data['dateDEffet']));
        $prestation->setDateDeFin(new \DateTime($data['dateDeFin']));
        $prestation->setType($data['type']);
        $prestation->setDateDeCreation(new \DateTimeImmutable());
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

    #[Route('/api/prestations/{id}', name: 'update_prestation', methods: ['PUT'])]
    public function updatePrestation(int $id, Request $request, EntityManagerInterface $entityManager, PrestationRepository $prestationRepository, ValidatorInterface $validator): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (is_null($data)) {
            return new JsonResponse(['message' => 'Données invalides.'], 400);
        }

        $prestation->setTitre($data['titre'] ?? $prestation->getTitre());
        $prestation->setDescription($data['description'] ?? $prestation->getDescription());
        $prestation->setDateDEffet(isset($data['dateDEffet']) ? new \DateTime($data['dateDEffet']) : $prestation->getDateDEffet());
        $prestation->setDateDeFin(isset($data['dateDeFin']) ? new \DateTime($data['dateDeFin']) : $prestation->getDateDeFin());
        $prestation->setType($data['type'] ?? $prestation->getType());

        $errors = $validator->validate($prestation);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($prestation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation mise à jour avec succès'], 200);
    }

    #[Route('/api/prestations/{id}', name: 'delete_prestation', methods: ['DELETE'])]
    public function deletePrestation(int $id, PrestationRepository $prestationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée'], 404);
        }

        $entityManager->remove($prestation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation supprimée avec succès'], 200);
    }
}
