<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardUserController extends AbstractController
{
    #[Route('/http://localhost:5173/components/dashboard/VoyageurDashboard', name: 'voyageur_dashboard')]
    public function voyageurDashboard(): Response
    {
        // Rediriger vers votre route React pour le tableau de bord du voyageur
        return $this->redirectToRoute('react_voyageur_dashboard');
    }

    #[Route('/proprietaire/dashboard', name: 'proprietaire_dashboard')]
    public function proprietaireDashboard(): Response
    {
        // Rediriger vers votre route React pour le tableau de bord du propriétaire
        return $this->redirectToRoute('react_proprietaire_dashboard');
    }

    #[Route('/prestataire/dashboard', name: 'prestataire_dashboard')]
    public function prestataireDashboard(): Response
    {
        // Rediriger vers votre route React pour le tableau de bord du prestataire
        return $this->redirectToRoute('react_prestataire_dashboard');
    }
}
