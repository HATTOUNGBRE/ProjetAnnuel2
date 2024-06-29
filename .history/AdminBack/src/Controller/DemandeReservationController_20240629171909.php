<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DemandeReservationController extends AbstractController
{
    #[Route('/demande/reservation', name: 'app_demande_reservation')]
    public function index(): Response
    {
        return $this->render('demande_reservation/index.html.twig', [
            'controller_name' => 'DemandeReservationController',
        ]);
    }
}
