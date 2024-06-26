<?php
// src/Controller/PrestationController.php

namespace App\Controller;

use App\Entity\Prestation;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;



class PrestationController extends AbstractController
{
    #[Route('/api/prestations', name: 'create_prestation', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $prestation = new Prestation();
        $prestation->setTitre($data['titre']);
        $prestation->setDescription($data['description']);
        $prestation->setDateDEffet(new \DateTimeImmutable($data['dateDEffet']));
        $prestation->setType($data['type']);
        $prestation->setDateDeCreation(new \DateTimeImmutable());
        $prestation->setStatut('en attente'); 
        $prestation->setActive(true); 
        $prestation->setUser($this->getUser());


        $errors = $validator->validate($prestation);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['message' => $errorsString], 400);
        }

        $entityManager->persist($prestation);
        $entityManager->flush();

        // Créer une réservation automatiquement
        $reservation = new Reservation();
        $reservation->setTitre($prestation->getTitre())
                    ->setDateDeCreation(new \DateTimeImmutable())
                    ->setDateDEffet(new \DateTimeImmutable($data['dateDEffet']))
                    ->setDateDeFin(new \DateTimeImmutable($data['dateDEffet']))
                    ->setStatut('en attente')
                    ->setActive(true)
                    ->setValide(false)
                    ->setPrestataire($this->getUser());

        $entityManager->persist($reservation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation et réservation créées avec succès'], 201);
    }
}
