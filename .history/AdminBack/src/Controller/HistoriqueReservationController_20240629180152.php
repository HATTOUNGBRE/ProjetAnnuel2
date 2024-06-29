<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoriqueReservationController extends AbstractController
{
    #[Route('/historique/reservation', name: 'app_historique_reservation')]
    public function index(): Response
    {
        return $this->render('historique_reservation/index.html.twig', [
            'controller_name' => 'HistoriqueReservationController',
        ]);
    }
}
