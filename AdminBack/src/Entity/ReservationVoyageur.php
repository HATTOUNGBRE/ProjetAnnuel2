<?php

namespace App\Entity;

use App\Repository\ReservationVoyageurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationVoyageurRepository::class)]
class ReservationVoyageur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateArrivee = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\ManyToOne(inversedBy: 'reservationVoyageurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $property = null;

    #[ORM\Column]
    private ?int $guestNb = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->dateArrivee;
    }

    public function setDateArrivee(\DateTimeInterface $dateArrivee): static
    {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getGuestNb(): ?int
    {
        return $this->guestNb;
    }

    public function setGuestNb(int $guestNb): static
    {
        $this->guestNb = $guestNb;

        return $this;
    }
}
