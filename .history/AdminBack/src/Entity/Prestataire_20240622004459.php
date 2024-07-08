<?php
// src/Entity/Prestataire.php

namespace App\Entity;

use App\Repository\PrestataireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrestataireRepository::class)]
class Prestataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['prestataire:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'prestataires')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['prestataire:read'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Groups(['prestataire:read'])]
    private ?string $type = null;

    #[ORM\Column(type: 'decimal', scale: 2)]
    #[Groups(['prestataire:read'])]
    private ?float $tarif = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['prestataire:read'])]
    private ?bool $verified = false;

    /**
     * @var Collection<int, Disponibilite>
     */
    #[ORM\OneToMany(targetEntity: Disponibilite::class, mappedBy: 'prestataire')]
    private Collection $disponibilites;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="prestataire")
     */
    private $reservations;
    public function __construct()
    {
        $this->disponibilites = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): static
    {
        $this->tarif = $tarif;
        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->verified;
    }

    public function setVerified(bool $verified): static
    {
        $this->verified = $verified;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getUser()->getName();
    }

    /**
     * @return Collection<int, Disponibilite>
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): static
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->add($disponibilite);
            $disponibilite->setPrestataire($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): static
    {
        if ($this->disponibilites->removeElement($disponibilite)) {
            // set the owning side to null (unless already changed)
            if ($disponibilite->getPrestataire() === $this) {
                $disponibilite->setPrestataire(null);
            }
        }

        return $this;
    }
}
