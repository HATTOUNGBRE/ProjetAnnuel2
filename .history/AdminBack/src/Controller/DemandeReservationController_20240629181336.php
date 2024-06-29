<?
namespace App\Controller;

use App\Entity\DemandeReservation;
use App\Entity\HistoriqueReservation;
use App\Repository\DemandeReservationRepository;
use App\Repository\HistoriqueReservationRepository;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DemandeReservationController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $demandeRepository;
    private $historiqueRepository;
    

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, DemandeReservationRepository $demandeRepository, HistoriqueReservationRepository $historiqueRepository)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->demandeRepository = $demandeRepository;
        $this->historiqueRepository = $historiqueRepository;
    }

    #[Route('/api/demandes', name: 'create_demande_reservation', methods: ['POST'])]
    public function createDemandeReservation(Request $request, PropertyRepository $propertyRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $property = $propertyRepository->find($data['property']);
        if (!$property) {
            return new JsonResponse(['message' => 'Property not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $demande = new DemandeReservation();
        $demande->setDateArrivee(new \DateTime($data['dateArrivee']));
        $demande->setDateDepart(new \DateTime($data['dateDepart']));
        $demande->setGuestNb($data['guestNb']);
        $demande->setProperty($property);
        $demande->setStatus('En attente');
        $demande->setCreatedAt(new \DateTime());
        $demande->setName($data['name']);
        $demande->setSurname($data['surname']);
        $demande->setVoyageurId($data['voyageurId']);

        $this->entityManager->persist($demande);

        $historique = new HistoriqueReservation();
        $historique->setDateArrivee(new \DateTime($data['dateArrivee']));
        $historique->setDateDepart(new \DateTime($data['dateDepart']));
        $historique->setGuestNb($data['guestNb']);
        $historique->setProperty($property);
        $historique->setStatus('En attente');
        $historique->setCreatedAt(new \DateTime());
        $historique->setName($data['name']);
        $historique->setSurname($data['surname']);
        $historique->setVoyageurId($data['voyageurId']);

        $this->entityManager->persist($historique);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($demande, 'json', ['groups' => 'demande:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_CREATED, [], true);
    }

    #[Route('/api/demandes/voyageur/{voyageurId}', name: 'get_demandes_voyageur', methods: ['GET'])]
    public function getDemandesVoyageur(int $voyageurId): JsonResponse
    {
        $demandes = $this->demandeRepository->findBy(['voyageurId' => $voyageurId]);

        if (!$demandes) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        return $this->json($demandes, 200, [], ['groups' => 'demande:read']);
    }
}
