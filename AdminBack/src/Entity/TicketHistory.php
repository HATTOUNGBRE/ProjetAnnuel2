<?php

namespace App\Entity;

use App\Repository\TicketHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketHistoryRepository::class)]
class TicketHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ticket_id = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $changed_by = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $changed_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketId(): ?int
    {
        return $this->ticket_id;
    }

    public function setTicketId(int $ticket_id): static
    {
        $this->ticket_id = $ticket_id;

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

    public function getChangedBy(): ?int
    {
        return $this->changed_by;
    }

    public function setChangedBy(int $changed_by): static
    {
        $this->changed_by = $changed_by;

        return $this;
    }

    public function getChangedAt(): ?\DateTimeInterface
    {
        return $this->changed_at;
    }

    public function setChangedAt(\DateTimeInterface $changed_at): static
    {
        $this->changed_at = $changed_at;

        return $this;
    }
}
