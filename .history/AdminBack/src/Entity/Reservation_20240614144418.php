<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['reservation:read'])]
    private ?string $titre = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $dateDeCreation = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $dateDEffet = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $dateDeFin = null;

    #[ORM\Column(length: 255)]
    #[Groups(['reservation:read'])]
    private ?string $statut = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['reservation:read'])]
    private ?bool $active = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->dateDeCreation;
    }

    public function setDateDeCreation(\DateTimeInterface $dateDeCreation): static
    {
        $this->dateDeCreation = $dateDeCreation;

        return $this;
    }

    public function getDateDEffet(): ?\DateTimeInterface
    {
        return $this->dateDEffet;
    }

    public function setDateDEffet(\DateTimeInterface $dateDEffet): static
    {
        $this->dateDEffet = $dateDEffet;

        return $this;
    }

    public function getDateDeFin(): ?\DateTimeInterface
    {
        return $this->dateDeFin;
    }

    public function setDateDeFin(\DateTimeInterface $dateDeFin): static
    {
        $this->dateDeFin = $dateDeFin;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
