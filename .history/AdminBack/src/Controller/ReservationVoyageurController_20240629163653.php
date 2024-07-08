<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReservationVoyageurController extends AbstractController
{
    #[Route('/reservation/voyageur', name: 'app_reservation_voyageur')]
    public function index(): Response
    {
        return $this->render('reservation_voyageur/index.html.twig', [
            'controller_name' => 'ReservationVoyageurController',
        ]);
    }
}
