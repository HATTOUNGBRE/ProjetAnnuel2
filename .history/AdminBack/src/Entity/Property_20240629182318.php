<?php
// src/Entity/Property.php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['property:read', 'property:write'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $proprio = null;

    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?int $maxPersons = null;

    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $hasPool = null;

    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?int $area = null;

    #[ORM\Column]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $hasBalcony = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $commune = null;

    /**
     * @var Collection<int, ReservationVoyageur>
     */
    #[ORM\OneToMany(targetEntity: ReservationVoyageur::class, mappedBy: 'property', orphanRemoval: true)]
    #[Groups(['property:read'])]
    private Collection $reservationVoyageurs;

    public function __construct()
    {
        $this->reservationVoyageurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image ? '/uploads/property_photos/' . $this->image : null;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getProprio(): ?User
    {
        return $this->proprio;
    }

    public function setProprio(?User $proprio): static
    {
        $this->proprio = $proprio;
        return $this;
    }

    public function getMaxPersons(): ?int
    {
        return $this->maxPersons;
    }

    public function setMaxPersons(int $maxPersons): static
    {
        $this->maxPersons = $maxPersons;
        return $this;
    }

    public function hasPool(): ?bool
    {
        return $this->hasPool;
    }

    public function setHasPool(bool $hasPool): static
    {
        $this->hasPool = $hasPool;
        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): static
    {
        $this->area = $area;
        return $this;
    }

    public function hasBalcony(): ?bool
    {
        return $this->hasBalcony;
    }

    public function setHasBalcony(bool $hasBalcony): static
    {
        $this->hasBalcony = $hasBalcony;
        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): self
    {
        $this->commune = $commune;
        return $this;
    }

    /**
     * @return Collection<int, ReservationVoyageur>
     */
    public function getReservationVoyageurs(): Collection
    {
        return $this->reservationVoyageurs;
    }

    public function addReservationVoyageur(ReservationVoyageur $reservationVoyageur): self
    {
        if (!$this->reservationVoyageurs->contains($reservationVoyageur)) {
            $this->reservationVoyageurs[] = $reservationVoyageur;
            $reservationVoyageur->setProperty($this);
        }

        return $this;
    }

    public function removeReservationVoyageur(ReservationVoyageur $reservationVoyageur): self
    {
        if ($this->reservationVoyageurs->removeElement($reservationVoyageur)) {
            // set the owning side to null (unless already changed)
            if ($reservationVoyageur->getProperty() === $this) {
                $reservationVoyageur->setProperty(null);
            }
        }

        return $this;
    }
}
