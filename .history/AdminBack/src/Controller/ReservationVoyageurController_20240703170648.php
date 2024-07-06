<?php
// src/Controller/ReservationVoyageurController.php

namespace App\Controller;

use App\Repository\ReservationVoyageurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ReservationVoyageurController extends AbstractController
{
    private $entityManager;
    private $reservationVoyageurRepository;
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager, 
        ReservationVoyageurRepository $reservationVoyageurRepository, 
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->reservationVoyageurRepository = $reservationVoyageurRepository;
        $this->serializer = $serializer;
    }

    #[Route('/api/properties/{id}/reservations', name: 'get_property_reservations', methods: ['GET'])]
    public function getPropertyReservations(int $id): JsonResponse
    {
        $reservations = $this->reservationVoyageurRepository->findBy(['property' => $id]);

        $responseData = $this->serializer->serialize($reservations, 'json', ['groups' => 'reservation:read']);
        return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
    }
}
