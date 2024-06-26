<? 
namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Prestataire;
use App\Entity\Prestation;
use App\Repository\PrestataireRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class ReservationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user-reservations/{userId}', name: 'app_user_reservations', methods: ['GET'])]
    public function getUserReservations(int $userId, ReservationRepository $reservationRepository): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $reservations = $reservationRepository->findBy(['user' => $user]);

        $data = [];
        foreach ($reservations as $reservation) {
            $data[] = [
                'id' => $reservation->getId(),
                'titre' => $reservation->getTitre(),
                'dateDEffet' => $reservation->getDateDEffet()->format('Y-m-d H:i:s'),
                'dateDeFin' => $reservation->getDateDeFin()->format('Y-m-d H:i:s'),
                'statut' => $reservation->getStatut(),
                'valide' => $reservation->getValide(),
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName()
                ]
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/reservations/unassigned', name: 'api_reservations_unassigned', methods: ['GET'])]
public function getUnassignedReservations(ReservationRepository $reservationRepository): JsonResponse
{
    $reservations = $reservationRepository->findBy(['prestataire' => null]);

    $data = [];
    foreach ($reservations as $reservation) {
        $data[] = [
            'id' => $reservation->getId(),
            'titre' => $reservation->getTitre(),
            'dateDEffet' => $reservation->getDateDEffet()->format('Y-m-d H:i:s'),
            'dateDeFin' => $reservation->getDateDeFin()->format('Y-m-d H:i:s'),
            'statut' => $reservation->getStatut(),
        ];
    }

    return new JsonResponse($data, Response::HTTP_OK);
}

#[Route('/reservation/{id}', name: 'api_reservation_detail', methods: ['GET'])]
public function getReservationDetail(int $id, ReservationRepository $reservationRepository): JsonResponse
{
    $reservation = $reservationRepository->find($id);

    if (!$reservation) {
        return new JsonResponse(['error' => 'Reservation not found'], Response::HTTP_NOT_FOUND);
    }

    $data = [
        'id' => $reservation->getId(),
        'titre' => $reservation->getTitre(),
        'dateDEffet' => $reservation->getDateDEffet()->format('Y-m-d H:i:s'),
        'dateDeFin' => $reservation->getDateDeFin()->format('Y-m-d H:i:s'),
        'statut' => $reservation->getStatut(),
        'prestation' => [
            'id' => $reservation->getPrestation()->getId(),
            'titre' => $reservation->getPrestation()->getTitre(),
            'description' => $reservation->getPrestation()->getDescription(),
            'type' => $reservation->getPrestation()->getType(),
        ],
    ];

    return new JsonResponse($data, Response::HTTP_OK);
}

#[Route('/reservation/{id}/candidature', name: 'candidature_prestataire', methods: ['POST'])]
    public function addCandidature(int $id, Request $request, ReservationRepository $reservationRepository, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['user_id'])) {
            return new JsonResponse(['error' => 'User ID is missing'], Response::HTTP_BAD_REQUEST);
        }

        $userId = $data['user_id'];
        $user = $userRepository->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], Response::HTTP_NOT_FOUND);
        }

        // Add logic to associate the candidature with the reservation
        // For example, you might add the prestataire to a list of candidates for the reservation
        // Here you should add the specific business logic as per your requirements

        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Candidature submitted successfully'], Response::HTTP_CREATED);
    }

    #[Route('/reservation/{id}/validate', name: 'validate_prestataire', methods: ['PUT'])]
    public function validateCandidature(int $id, Request $request): JsonResponse
    {
        $reservation = $this->entityManager->getRepository(Reservation::class)->find($id);

        if (!$reservation) {
            return new JsonResponse(['error' => 'Reservation not found'], Response::HTTP_NOT_FOUND);
        }

        $prestataireId = $request->request->get('prestataire_id');
        $prestataire = $this->entityManager->getRepository(Prestataire::class)->find($prestataireId);

        if (!$prestataire) {
            return new JsonResponse(['error' => 'Prestataire not found'], Response::HTTP_NOT_FOUND);
        }

        $reservation->setPrestataire($prestataire);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Prestataire validated successfully'], Response::HTTP_OK);
    }

    // Autres méthodes du contrôleur pour la gestion des réservations
}
