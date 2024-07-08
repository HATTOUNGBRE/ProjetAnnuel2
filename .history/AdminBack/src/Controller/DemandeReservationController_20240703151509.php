<?php
// src/Controller/DemandeReservationController.php

namespace App\Controller;

use App\Entity\DemandeReservation;
use App\Entity\HistoriqueReservation;
use App\Entity\ReservationVoyageur;
use App\Entity\Availability;
use App\Repository\DemandeReservationRepository;
use App\Repository\HistoriqueReservationRepository;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Utils\ReservationNumberGenerator;

class DemandeReservationController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $demandeRepository;
    private $historiqueRepository;
    

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer, DemandeReservationRepository $demandeRepository, HistoriqueReservationRepository $historiqueRepository)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->demandeRepository = $demandeRepository;
        $this->historiqueRepository = $historiqueRepository;
    }

    #[Route('/api/demandes', name: 'create_demande_reservation', methods: ['POST'])]
public function createDemandeReservation(Request $request, PropertyRepository $propertyRepository): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $property = $propertyRepository->find($data['property']);
    if (!$property) {
        return new JsonResponse(['message' => 'Property not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    $demande = new DemandeReservation();
    $demande->setDateArrivee(new \DateTime($data['dateArrivee']));
    $demande->setDateDepart(new \DateTime($data['dateDepart']));
    $demande->setGuestNb($data['guestNb']);
    $demande->setProperty($property);
    $demande->setStatus('En attente');
    $demande->setCreatedAt(new \DateTime());
    $demande->setName($data['name']);
    $demande->setSurname($data['surname']);
    $demande->setVoyageurId($data['voyageurId']);
    $demande->setReservationNumber(ReservationNumberGenerator::generate());
    $demande->setActive(false);
    $demande->setTotalPrice($property->getPrice() * $demande->getDateArrivee()->diff($demande->getDateDepart())->days);

    $historique = new HistoriqueReservation();
    $historique->setDateArrivee(new \DateTime($data['dateArrivee']));
    $historique->setDateDepart(new \DateTime($data['dateDepart']));
    $historique->setGuestNb($data['guestNb']);
    $historique->setProperty($property);
    $historique->setStatus('En attente');
    $historique->setCreatedAt(new \DateTime());
    $historique->setName($data['name']);
    $historique->setSurname($data['surname']);
    $historique->setVoyageurId($data['voyageurId']);
    $historique->setTotalPrice($demande->getTotalPrice());
    $historique->setDemandeReservation($demande);
    $historique->setReservationNumber($demande->getReservationNumber());

    $this->entityManager->persist($demande);
    $this->entityManager->persist($historique);
    $this->entityManager->flush();

    $responseData = $this->serializer->serialize($demande, 'json', ['groups' => 'demande:read']);
    return new JsonResponse($responseData, JsonResponse::HTTP_CREATED, [], true);
}

#[Route('/api/demandes/{id}', name: 'update_demande_reservation', methods: ['PUT'])]
public function updateDemandeReservation(int $id, Request $request, PropertyRepository $propertyRepository, HistoriqueReservationRepository $historiqueRepository): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    if (!$data) {
        return new JsonResponse(['message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $demande = $this->demandeRepository->find($id);
    if (!$demande) {
        return new JsonResponse(['message' => 'Demande not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    $property = $demande->getProperty();
    $dateArrivee = new \DateTime($data['dateArrivee']);
    $dateDepart = new \DateTime($data['dateDepart']);
    if ($dateArrivee >= $dateDepart) {
        return new JsonResponse(['message' => 'La date d\'arrivée doit être avant la date de départ'], JsonResponse::HTTP_BAD_REQUEST);
    }

    $totalPrice = $property->getPrice() * $dateArrivee->diff($dateDepart)->days;

    $demande->setDateArrivee($dateArrivee);
    $demande->setDateDepart($dateDepart);
    $demande->setGuestNb($data['guestNb']);
    $demande->setTotalPrice($totalPrice);
    $demande->setUpdatedAt(new \DateTime());

    $historique = $historiqueRepository->findOneBy(['demandeReservation' => $demande]);
    if ($historique) {
        $historique->setDateArrivee($dateArrivee);
        $historique->setDateDepart($dateDepart);
        $historique->setGuestNb($data['guestNb']);
        $historique->setTotalPrice($totalPrice);
        $historique->setUpdatedAt(new \DateTime());
    }

    $this->entityManager->flush();

    $responseData = $this->serializer->serialize($demande, 'json', ['groups' => 'demande:read']);
    return new JsonResponse($responseData, JsonResponse::HTTP_OK, [], true);
}

    

    #[Route('/api/demandes/voyageur/{voyageurId}', name: 'get_demandes_voyageur', methods: ['GET'])]
    public function getDemandesVoyageur(int $voyageurId): JsonResponse
    {
        $demandes = $this->demandeRepository->findBy(['voyageurId' => $voyageurId]);

        if (!$demandes) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        return $this->json($demandes, 200, [], ['groups' => 'demande:read']);
    }

    #[Route('/api/demandes/{id}/cancel', name: 'cancel_demande_reservation', methods: ['POST'])]
    public function cancelDemandeReservation(int $id): JsonResponse
    {
        $demande = $this->demandeRepository->find($id);
        if (!$demande) {
            return new JsonResponse(['message' => 'Demande not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $demande->setStatus('Annulée');
        $this->entityManager->flush();

        // Mettre à jour l'historique
        $historique = $this->historiqueRepository->findOneBy(['voyageurId' => $demande->getVoyageurId(), 'property' => $demande->getProperty(), 'dateArrivee' => $demande->getDateArrivee(), 'dateDepart' => $demande->getDateDepart()]);
        if ($historique) {
            $historique->setStatus('Annulée');
            $this->entityManager->flush();
        }

        return new JsonResponse(['message' => 'Demande annulée avec succès'], JsonResponse::HTTP_OK);
    }
    #[Route('/api/demandes/active', name: 'get_active_demandes', methods: ['GET'])]
    public function getActiveDemandes(): JsonResponse
    {
        $demandes = $this->demandeRepository->findBy(['active' => true]);
        return $this->json($demandes, 200, [], ['groups' => 'demande:read']);
    }


    #########################################Gérer les demandes acceptées#########################################
    #[Route('/api/demandes/{id}/accept', name: 'accept_demande_reservation', methods: ['POST'])]
public function acceptDemandeReservation(int $id): JsonResponse
{
    $demande = $this->demandeRepository->find($id);
    if (!$demande) {
        return new JsonResponse(['message' => 'Demande not found'], JsonResponse::HTTP_NOT_FOUND);
    }

    $demande->setStatus('Accepted');
    $demande->setActive(true);

    // Créer une nouvelle réservation
    $reservation = new ReservationVoyageur();
    $reservation->setDateArrivee($demande->getDateArrivee());
    $reservation->setDateDepart($demande->getDateDepart());
    $reservation->setProperty($demande->getProperty());
    $reservation->setGuestNb($demande->getGuestNb());
    $reservation->setReservationNumber($demande->getReservationNumber());
    $reservation->setVoyageurId($demande->getVoyageurId());
    $reservation->setTotalPrice($demande->getTotalPrice());

    // Créer une nouvelle disponibilité pour cette réservation
    $availability = new Availability();
    $availability->setProperty($demande->getProperty());
    $availability->setStartDate($demande->getDateArrivee());
    $availability->setEndDate($demande->getDateDepart());

    $this->entityManager->persist($reservation);
    $this->entityManager->persist($availability);
    $this->entityManager->flush();

    // Mettre à jour l'historique
    $historique = $this->historiqueRepository->findOneBy([
        'voyageurId' => $demande->getVoyageurId(),
        'property' => $demande->getProperty(),
        'dateArrivee' => $demande->getDateArrivee(),
        'dateDepart' => $demande->getDateDepart()
    ]);
    if ($historique) {
        $historique->setStatus('Accepted');
        $this->entityManager->flush();
    }

    return new JsonResponse(['message' => 'Demande accepted and reservation created successfully'], JsonResponse::HTTP_OK);
}

   
}
