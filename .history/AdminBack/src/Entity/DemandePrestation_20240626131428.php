<?php

namespace App\Entity;

use App\Repository\DemandePrestationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=DemandePrestationRepository::class)
 */
class DemandePrestation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */

    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */
    private ?string $titre = null;

    /**
     * @ORM\Column(type="text")
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */
    private ?\DateTimeInterface $dateDEffet = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */
    private ?string $type = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */
    private ?string $statut = 'en attente';

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"demande_prestation:read", "demande_prestation:write"})
     */
    private ?User $user = null;

    // Getters and Setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDEffet(): ?\DateTimeInterface
    {
        return $this->dateDEffet;
    }

    public function setDateDEffet(\DateTimeInterface $dateDEffet): self
    {
        $this->dateDEffet = $dateDEffet;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    // Getters and Setters...


}
