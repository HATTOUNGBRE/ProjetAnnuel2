<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Prestation;
use App\Entity\User;
use App\Entity\Prestataire;


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
    #[Assert\Choice(choices: ['accepté', 'refusé', 'annulé', 'terminé', 'en attente'], message: 'Choisissez un statut valide.')]
    private ?string $statut = null;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['reservation:read'])]
    private ?bool $active = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $valide = null;

    #[ORM\ManyToOne(targetEntity: Prestation::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prestation $prestation = null;

 


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

/   #[ORM\ManyToOne(targetEntity: Prestataire::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: true)]
    private $candidatures;

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }
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

    public function getValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }

    public function getPrestation(): ?Prestation
    {
        return $this->prestation;
    }

    public function setPrestation(?Prestation $prestation): self
    {
        $this->prestation = $prestation;

        return $this;
    }

    public function getPrestataire(): ?Prestataire
    {
        return $this->prestataire;
    }

    public function setPrestataire(?Prestataire $prestataire): self
    {
        $this->prestataire = $prestataire;

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

    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    /**
     * @return Collection|Prestataire[]
     */
    
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Prestataire $prestataire): self
    {
        if (!$this->candidatures->contains($prestataire)) {
            $this->candidatures[] = $prestataire;
        }

        return $this;
    }

    public function removeCandidature(Prestataire $prestataire): self
    {
        $this->candidatures->removeElement($prestataire);

        return $this;
    }
}
