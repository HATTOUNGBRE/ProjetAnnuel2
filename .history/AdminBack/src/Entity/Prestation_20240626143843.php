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
    #[ORM\Column(type: 'integer')]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?\DateTimeInterface $dateDEffet = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['prestation:read', 'prestation:write'])]
    * @ORM\Column(type="datetime", nullable=true)

    private ?\DateTimeInterface $dateDeFin = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?string $type = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?\DateTimeImmutable $dateDeCreation = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['prestation:read', 'prestation:write'])]
    private ?User $user = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeImmutable
    {
        return $this->dateDeCreation;
    }

    public function setDateDeCreation(\DateTimeImmutable $dateDeCreation): static
    {
        $this->dateDeCreation = $dateDeCreation;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function __toString(): string
    {
        return $this->titre;
    }
}
