<?php
// src/Controller/HistoriqueReservationController.php

namespace App\Controller;

use App\Repository\HistoriqueReservationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Utils\ReservationNumberGenerator;


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
    
}
