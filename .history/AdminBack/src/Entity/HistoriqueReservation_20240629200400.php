<?php
namespace App\Entity;

use App\Repository\HistoriqueReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: HistoriqueReservationRepository::class)]
class HistoriqueReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['historique:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['historique:read'])]

    private ?\DateTimeInterface $dateArrivee = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['historique:read'])]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\ManyToOne(inversedBy: 'historiques')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?Property $property = null;

    #[ORM\Column]
    #[Groups(['historique:read',    'historique:write'])]
    private ?int $guestNb = null;

    #[ORM\Column(length: 255)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?string $surname = null;

    #[ORM\Column]
    #[Groups(['historique:read', 'historique:write'])]
    private ?int $voyageurId = null;
    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['historique:read', 'historique:write'])]
    private ?float $totalPrice = null;

    #[ORM\ManyToOne(targetEntity: DemandeReservation::class)]
    private ?DemandeReservation $demandeReservation = null;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;
        return $this;
    }

    public function getVoyageurId(): ?int
    {
        return $this->voyageurId;
    }

    public function setVoyageurId(int $voyageurId): static
    {
        $this->voyageurId = $voyageurId;
        return $this;
    }
    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): static
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }
}
