<?php

namespace App\EventListener;

use App\Entity\DemandeReservation;
use App\Entity\ReservationVoyageur;
use App\Entity\Payment;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs as DoctrineLifecycleEventArgs;

class DemandeReservationListener
{
    public function preUpdate(DemandeReservation $demandeReservation, LifecycleEventArgs $args)
    {
        if ($demandeReservation->getStatus() === 'Acceptée') {
            $entityManager = $args->getEntityManager();

            // Création de la réservation voyageur
            $reservation = new ReservationVoyageur();
            $reservation->setDateArrivee($demandeReservation->getDateArrivee());
            $reservation->setDateDepart($demandeReservation->getDateDepart());
            $reservation->setGuestNb($demandeReservation->getGuestNb());
            $reservation->setTotalPrice($demandeReservation->getTotalPrice());
            $reservation->setProperty($demandeReservation->getProperty());
            $reservation->setVoyageurId($demandeReservation->getVoyageurId());
            $reservation->setReservationNumber($demandeReservation->getReservationNumber());

            $entityManager->persist($reservation);

            // Création du paiement
            $payment = new Payment();
            $payment->setDate(new \DateTime());
            $payment->setAmount($demandeReservation->getTotalPrice());
            $payment->setMethod('credit_card'); // Remplacez par la méthode réelle
            $payment->setReservation($reservation);

            $entityManager->persist($payment);

            $entityManager->flush();
        }
    }
}
