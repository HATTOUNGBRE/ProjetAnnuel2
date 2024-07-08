<? 
namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ReservationController extends AbstractController
{
    #[Route('/user-reservations/{userId}', name: 'app_user_reservations', methods: ['GET'])]
    public function getUserReservations(int $userId, ReservationRepository $reservationRepository): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
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
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName()
                ]
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    // Autres méthodes du contrôleur pour la gestion des réservations
}
