<?
namespace App\Controller;

use App\Entity\HistoriqueReservation;
use App\Repository\HistoriqueReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueReservationController extends AbstractController
{
    private $historiqueRepository;

    public function __construct(HistoriqueReservationRepository $historiqueRepository)
    {
        $this->historiqueRepository = $historiqueRepository;
    }

    #[Route('/api/historique/voyageur/{voyageurId}', name: 'get_historique_voyageur', methods: ['GET'])]
    public function getHistoriqueVoyageur(int $voyageurId): JsonResponse
    {
        $historiques = $this->historiqueRepository->findBy(['voyageurId' => $voyageurId]);
        return $this->json($historiques, 200, [], ['groups' => 'historique:read']);
    }
}
