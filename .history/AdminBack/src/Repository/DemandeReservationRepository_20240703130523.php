<?php
// src/Repository/DemandeReservationRepository.php

namespace App\Repository;

use App\Entity\DemandeReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeReservation>
 *
 * @method DemandeReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeReservation[]    findAll()
 * @method DemandeReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeReservation::class);
    }

    // Ajoutez vos méthodes personnalisées ici
}
