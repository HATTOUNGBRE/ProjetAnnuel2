<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\PrestationRepository;
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

        // Ajout de log pour voir les données reçues
        error_log(print_r($data, true));

        if (!isset($data['titre'], $data['description'], $data['dateDEffet'], $data['type'], $data['userId'])) {
            return new JsonResponse(['message' => 'Données manquantes'], 400);
        }

        $prestation = new Prestation();
        $prestation->setTitre($data['titre']);
        $prestation->setDescription($data['description']);
        $prestation->setDateDEffet(new \DateTime($data['dateDEffet']));
        $prestation->setType($data['type']);
        $prestation->setUser($entityManager->getRepository(User::class)->find($data['userId']));

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
                    ->setDateDeFin(new \DateTimeImmutable($data['dateDEffet'])) // Set same as DateDEffet
                    ->setStatut('en attente')
                    ->setActive(true)
                    ->setValide(false)
                    ->setPrestation($prestation);

        $entityManager->persist($reservation);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Prestation et réservation créées avec succès'], 201);
    }
}
