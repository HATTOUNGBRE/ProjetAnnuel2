<?
namespace App\Controller;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;

class PrestationController extends AbstractController
{
    #[Route('/api/prestations', name: 'get_prestations', methods: ['GET'])]
    public function getPrestations(PrestationRepository $prestationRepository, SerializerInterface $serializer): JsonResponse
    {
        $prestations = $prestationRepository->findAll();
        $data = [];

        foreach ($prestations as $prestation) {
            $data[] = [
                'id' => $prestation->getId(),
                'titre' => $prestation->getTitre(),
                'description' => $prestation->getDescription(),
                'dateDEffet' => $prestation->getDateDEffet() ? $prestation->getDateDEffet()->format('Y-m-d') : null,
                'dateDeFin' => $prestation->getDateDeFin() ? $prestation->getDateDeFin()->format('Y-m-d') : null,
                'type' => $prestation->getType(),
                'dateDeCreation' => $prestation->getDateDeCreation() ? $prestation->getDateDeCreation()->format('Y-m-d') : null,
                'user' => [
                    'id' => $prestation->getUser() ? $prestation->getUser()->getId() : null,
                    'name' => $prestation->getUser() ? $prestation->getUser()->getName() : null,
                ]
            ];
        }

        $jsonData = $serializer->serialize($data, 'json');
        return new JsonResponse($jsonData, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/prestations/{id}/accept', name: 'accept_prestation', methods: ['POST'])]
    public function acceptPrestation(int $id, EntityManagerInterface $entityManager, PrestationRepository $prestationRepository, LoggerInterface $logger): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée.'], 404);
        }

        $prestation->setStatut('acceptée');
        $prestation->setDateDeFin((clone $prestation->getDateDEffet())->modify('+1 day'));

        $entityManager->persist($prestation);
        $entityManager->flush();

        // Log the acceptance to a file
        $message = sprintf("Prestation %d acceptée par l'utilisateur %d\n", $prestation->getId(), $this->getUser()->getId());
        file_put_contents('prestation_log.txt', $message, FILE_APPEND);

        return new JsonResponse(['message' => 'Prestation acceptée avec succès.'], 200);
    }

    #[Route('/api/prestations/{id}/reject', name: 'reject_prestation', methods: ['POST'])]
    public function rejectPrestation(int $id, EntityManagerInterface $entityManager, PrestationRepository $prestationRepository, LoggerInterface $logger): JsonResponse
    {
        $prestation = $prestationRepository->find($id);

        if (!$prestation) {
            return new JsonResponse(['message' => 'Prestation non trouvée.'], 404);
        }

        $prestation->setStatut('refusée');

        $entityManager->persist($prestation);
        $entityManager->flush();

        // Log the rejection to a file
        $message = sprintf("Prestation %d refusée par l'utilisateur %d\n", $prestation->getId(), $this->getUser()->getId());
        file_put_contents('prestation_log.txt', $message, FILE_APPEND);

        return new JsonResponse(['message' => 'Prestation refusée avec succès.'], 200);
    }
}
