<?php
// src/Entity/CheckVoyageur.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: 'App\Repository\CheckVoyageurRepository')]
class CheckVoyageur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['check:read', 'check:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ReservationVoyageur::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['check:read', 'check:write'])]
    private ?ReservationVoyageur $reservation = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['check:read', 'check:write'])]
    private ?\DateTimeInterface $checkIn = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['check:read', 'check:write'])]
    private ?\DateTimeInterface $checkOut = null;

    // Getters and Setters...

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(?\DateTimeInterface $checkIn): self
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(?\DateTimeInterface $checkOut): self
    {
        $this->checkOut = $checkOut;

        return $this;
    }
}
