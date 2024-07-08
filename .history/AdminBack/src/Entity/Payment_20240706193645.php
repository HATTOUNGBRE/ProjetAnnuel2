<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\PaymentRepository")]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: "decimal", scale: 2)]
    private ?float $amount = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $method = null;

    #[ORM\ManyToOne(targetEntity: ReservationVoyageur::class, inversedBy: "payments")]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReservationVoyageur $reservation = null;

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
}
