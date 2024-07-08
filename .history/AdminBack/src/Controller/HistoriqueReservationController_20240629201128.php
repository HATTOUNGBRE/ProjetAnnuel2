<?php
// src/Controller/HistoriqueReservationController.php

namespace App\Controller;

use App\Repository\HistoriqueReservationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        if (!$historiques) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        return $this->json($historiques, 200, [], ['groups' => 'historique:read']);
    }
    #[Route('/api/demandes/{id}', name: 'update_demande_reservation', methods: ['PUT'])]
public function updateDemandeReservation(int $id, Request $request, PropertyRepository $propertyRepository, HistoriqueReservationRepository $historiqueRepository): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $demande = $this->demandeRepository->find($id);
    if (!$demande) {
        return new JsonResponse(['message' => 'Demande not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    $property = $demande->getProperty();
    $dateArrivee = new \DateTime($data['dateArrivee']);
    $dateDepart = new \DateTime($data['dateDepart']);
    if ($dateArrivee >= $dateDepart) {
        return new JsonResponse(['message' => 'La date d\'arrivée doit être avant la date de départ'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $totalPrice = $property->getPrice() * $dateArrivee->diff($dateDepart)->days;

    $demande->setDateArrivee($dateArrivee);
    $demande->setDateDepart($dateDepart);
    $demande->setGuestNb($data['guestNb']);
    $demande->setTotalPrice($totalPrice);
    $demande->setUpdatedAt(new \DateTime());

    // Update corresponding entry in HistoriqueReservation
    $historique = $historiqueRepository->findOneBy(['demandeReservation' => $demande]);
    if ($historique) {
        $historique->setDateArrivee($dateArrivee);
        $historique->setDateDepart($dateDepart);
        $historique->setGuestNb($data['guestNb']);
        $historique->setTotalPrice($totalPrice);
        $historique->setUpdatedAt(new \DateTime());
    }

    $this->entityManager->flush();

    $responseData = $this->serializer->serialize($demande, 'json', ['groups' => 'demande:read']);
    return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
}

}
