<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

# @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]

    private ?int $id = null;

    #[ORM\Column(type: "date")]

    private ?\DateTimeInterface $date = null;

   
    #[ORM\Column(type: "string", length: 255, scale=2)]
    private ?string $amount = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $method = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
