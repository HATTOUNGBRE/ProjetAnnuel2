<?php
// src/Entity/CheckVoyageur.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CheckVoyageurRepository;
use App\Entity\DemandeReservation;

#[ORM\Entity(repositoryClass: 'App\Repository\CheckVoyageurRepository')]
class CheckVoyageur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['check:read', 'check:write'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: DemandeReservation::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['check:read', 'check:write'])]
    private ?DemandeReservation $demandeReservation = null;

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

    public function getDemandeReservation(): ?DemandeReservation
    {
        return $this->demandeReservation;
    }

    public function setDemandeReservation(?DemandeReservation $demandeReservation): self
    {
        $this->demandeReservation = $demandeReservation;

        return $this;
    }
}
