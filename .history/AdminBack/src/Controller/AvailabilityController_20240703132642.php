<?php
// src/Controller/AvailabilityController.php

namespace App\Controller;

use App\Repository\AvailabilityRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvailabilityController extends AbstractController
{
    private $availabilityRepository;

    public function __construct(AvailabilityRepository $availabilityRepository)
    {
        $this->availabilityRepository = $availabilityRepository;
    }

    #[Route('/api/availabilities', name: 'get_availabilities', methods: ['GET'])]
    public function getAvailabilities(): JsonResponse
    {
        $availabilities = $this->availabilityRepository->findAll();
        return $this->json($availabilities, 200, [], ['groups' => 'availability:read']);
    }
}
