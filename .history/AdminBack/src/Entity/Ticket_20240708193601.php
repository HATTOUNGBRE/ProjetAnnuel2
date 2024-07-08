<?php
// src/Entity/Ticket.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Utils\ReservationNumberGenerator;

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

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['ticket:read', 'ticket:write'])]
    private $status;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['ticket:read'])]
    private $ticketNumber;

    // Getters and Setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTicketNumber(): ?string
    {
        return $this->ticketNumber;
    }

    public function setTicketNumber(string $ticketNumber): self
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }



    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = 'Ouvert';
        $this->ticketNumber = ReservationNumberGenerator::generate();
    }

}
