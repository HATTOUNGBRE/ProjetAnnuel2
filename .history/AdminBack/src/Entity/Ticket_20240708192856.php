<?php
// src/Entity/Ticket.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $surname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $role;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $question;

    #[ORM\Column(type: 'text')]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $message;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['ticket:read'])]
    private $createdAt;

    // Getters and Setters...

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }
}
