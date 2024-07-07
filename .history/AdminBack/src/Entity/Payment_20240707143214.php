<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: "App\Repository\PaymentRepository")]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    #[Groups(['payment:read, payment:write', ])]    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: "decimal", scale: 2)]

    private ?float $amount = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['payment:read, payment:write', ])]
    private ?string $method = null;

    #[ORM\ManyToOne(targetEntity: ReservationVoyageur::class, inversedBy: "payments")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['payment:read, payment:write', ])]
    private ?ReservationVoyageur $reservation = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['payment:read', ])]
    private ?string $cardLast4 = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['payment:read', ])]
    private ?string $firstName = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['payment:read', ])]
    private ?string $lastName = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getReservation(): ?ReservationVoyageur
    {
        return $this->reservation;
    }

    public function setReservation(?ReservationVoyageur $reservation): self
    {
        $this->reservation = $reservation;
        return $this;
    }

    public function getCardLast4(): ?string
    {
        return $this->cardLast4;
    }

    public function setCardLast4(string $cardLast4): self
    {
        $this->cardLast4 = $cardLast4;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }
}
