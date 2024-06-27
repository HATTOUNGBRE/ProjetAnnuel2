<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DemandePrestationController extends AbstractController
{
    #[Route('/demande/prestation', name: 'app_demande_prestation')]
    public function index(): Response
    {
        return $this->render('demande_prestation/index.html.twig', [
            'controller_name' => 'DemandePrestationController',
        ]);
    }
}
