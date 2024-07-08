<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['product:read', 'product:write'])]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]

    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    #[Groups(['product:read', 'product:write'])]
    private ?bool $active = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['product:read', 'product:write'])]
    #[MaxDepth(1)]

    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    
    #[ORM\JoinColumn(nullable: false)]

    private ?User $proprio = null;

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

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
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
}
