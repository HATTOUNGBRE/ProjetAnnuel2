<?php
// src/Entity/Prestataire.php

namespace App\Entity;

use App\Repository\PrestataireRepository;
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
}
