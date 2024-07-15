<?php 
// src/Utils/TicketNumberGenerator.php

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tickets;

class TicketNumberGenerator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate(): int
    {
        do {
            $number = random_int(10000000, 99999999);
            $existingTicket = $this->entityManager->getRepository(Tickets::class)->findOneBy(['ticketNumber' => $number]);
        } while ($existingTicket !== null);

        return $number;
    }
}
