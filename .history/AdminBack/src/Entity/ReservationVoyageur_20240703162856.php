<?php

namespace App\Entity;

use App\Repository\ReservationVoyageurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ReservationVoyageurRepository::class)]
class ReservationVoyageur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $dateArrivee = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['reservation:read'])]
    private ?\DateTimeInterface $dateDepart = null;

    #[ORM\ManyToOne(inversedBy: 'reservationVoyageurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $property = null;



    #[ORM\Column]
    #[Groups(['reservation:read'])]
    private ?int $guestNb = null;

    #[ORM\Column(length: 10, unique: true)]
    
    #[Groups(['historique:read', 'historique:write', 'demande:read', 'demande:write', 'property:read', 'property:write'])]
    private ?string $reservationNumber = null;


    #[ORM\Column]
    #[Groups(['demande:read', 'demande:write'])]
    private ?int $voyageurId = null;


    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['demande:read', 'demande:write'])]
    private ?float $totalPrice = null;

    // Add a getter and setter for reservationNumber
    public function getReservationNumber(): ?string
    {
        return $this->reservationNumber;
    }

    public function setReservationNumber(string $reservationNumber): self
    {
        $this->reservationNumber = $reservationNumber;
        return $this;
    }

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
