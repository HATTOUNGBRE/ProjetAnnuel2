<?php

namespace App\Repository;

use App\Entity\DemandePrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<DemandePrestation>
 */
class DemandePrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandePrestation::class);
    }

    /**
     * @return DemandePrestation[] Returns an array of DemandePrestation objects with statut 'en attente'
     */
    public function findPendingDemandes(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.statut = :statut')
            ->setParameter('statut', 'en attente')
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
