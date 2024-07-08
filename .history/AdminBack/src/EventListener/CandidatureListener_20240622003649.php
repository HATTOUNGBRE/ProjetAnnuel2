<?php

namespace App\EventListener;

use App\Entity\Candidature;
use Doctrine\ORM\Event\PostUpdateEventArgs;

class CandidatureListener
{
    public function postUpdate(PostUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Candidature) {
            return;
        }

        if ($entity->isValidated()) {
            $reservation = $entity->getReservation();
            $prestataire = $entity->getPrestataire();
            $reservation->setPrestataire($prestataire);

            $entityManager = $args->getObjectManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
        }
    }
}
