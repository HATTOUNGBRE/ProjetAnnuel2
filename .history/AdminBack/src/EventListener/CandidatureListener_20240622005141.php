<?php
// src/EventListener/CandidatureListener.php

namespace App\EventListener;

use App\Entity\Candidature;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CandidatureListener
{
    public function postUpdate(Candidature $candidature, LifecycleEventArgs $args)
    {
        $entityManager = $args->getEntityManager();

        if ($candidature->isValidated()) {
            $reservation = $candidature->getReservation();
            $reservation->setPrestataire($candidature->getPrestataire());
            $entityManager->persist($reservation);
            $entityManager->flush();
        }
    }
}
