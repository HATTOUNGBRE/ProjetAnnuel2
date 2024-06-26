<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CandidatureRepository")
 */
class Candidature
{
    // ...

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prestataire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prestataire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reservation", inversedBy="candidatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    // Getters and setters...

    public function getPrestataire(): ?Prestataire
    {
        return $this->prestataire;
    }

    public function setPrestataire(?Prestataire $prestataire): self
    {
        $this->prestataire = $prestataire;
        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;
        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): self
    {
        $this->validated = $validated;
        return $this;
    }
}
