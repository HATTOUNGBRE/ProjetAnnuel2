<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrestataireController extends AbstractController
{
    #[Route('/prestataire', name: 'app_prestataire')]
    public function index(): Response
    {
        return $this->render('prestataire/index.html.twig', [
            'controller_name' => 'PrestataireController',
        ]);
    }
}
