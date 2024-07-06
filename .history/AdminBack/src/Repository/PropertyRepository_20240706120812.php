<?php
// src/Repository/PropertyRepository.php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * @param array $criteria
     * @return Property[]
     */
    public function findByCriteria(array $criteria)
    {
        $qb = $this->createQueryBuilder('p');

        if (isset($criteria['commune'])) {
            $qb->andWhere('p.commune = :commune')
               ->setParameter('commune', $criteria['commune']);
        }

        if (isset($criteria['maxPersons'])) {
            $qb->andWhere('p.maxPersons >= :maxPersons')
               ->setParameter('maxPersons', $criteria['maxPersons']);
        }

        return $qb->getQuery()->getResult();
    }
}
