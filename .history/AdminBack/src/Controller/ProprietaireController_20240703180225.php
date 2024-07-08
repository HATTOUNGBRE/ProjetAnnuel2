<?php
// src/Controller/ProprietaireController.php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{
    #[Route('/api/proprietaire/{id}/dashboard', name: 'proprietaire_dashboard', methods: ['GET'])]
    public function getDashboardData(int $id, PropertyRepository $propertyRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $properties = $propertyRepository->findBy(['proprio' => $id]);
        $totalProperties = count($properties);
        $occupiedProperties = 0;
        $earnings = 0;

        foreach ($properties as $property) {
            $reservations = $property->getReservations();
            if ($reservations) {
                $occupiedProperties++;
                foreach ($reservations as $reservation) {
                    $earnings += $reservation->getTotalPrice();
                }
            }
        }

        $vacantProperties = $totalProperties - $occupiedProperties;

        return new JsonResponse([
            'totalProperties' => $totalProperties,
            'occupiedProperties' => $occupiedProperties,
            'vacantProperties' => $vacantProperties,
            'earnings' => $earnings,
        ]);
    }
}
