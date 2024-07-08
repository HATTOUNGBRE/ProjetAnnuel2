<?php

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['prestation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['prestation:read'])]
    private ?string $titre = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['prestation:read'])]
    private ?\DateTimeInterface $dateDeCreation = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['prestation:read'])]
    private ?\DateTimeInterface $dateDEffet = null;

    #[ORM\Column(length: 255)]
    #[Groups(['prestation:read'])]
    private ?string $type = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['prestation:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['prestation:read'])]
    private ?string $statut = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['prestation:read'])]
    private ?bool $active = null;

    #[ORM\OneToMany(mappedBy: 'prestation', targetEntity: Reservation::class)]
    #[Groups(['prestation:read'])]
    private $reservations;


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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
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
    public function getReservations()
    {
        return $this->reservations;
    }

    public function setReservations($reservations): self
    {
        $this->reservations = $reservations;

        return $this;
    }
}
