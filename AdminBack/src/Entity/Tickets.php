<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TicketsRepository;

#[ORM\Entity(repositoryClass: TicketsRepository::class)]
class Tickets
{
    public const STATUS_NEW = 'nouveau';
    public const STATUS_IN_PROGRESS = 'en_cours';
    public const STATUS_RESOLVED = 'resolu';
    public const STATUS_CLOSED = 'ferme';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

   

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column(type: 'text')]
    private ?string $message = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Choice(choices: [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_RESOLVED, self::STATUS_CLOSED], message: 'Choose a valid status.')]
    private ?string $status = self::STATUS_NEW;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\Choice(choices: [self::PRIORITY_LOW, self::PRIORITY_MEDIUM, self::PRIORITY_HIGH], message: 'Choose a valid priority.')]
    private ?string $priority = self::PRIORITY_MEDIUM;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $ticketNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $assigned_to = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getTicketNumber(): ?string
    {
        return $this->ticketNumber;
    }

    public function setTicketNumber(string $ticketNumber): self
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    public function getAssignedTo(): ?string
    {
        return $this->assigned_to;
    }

    public function setAssignedTo(?string $assigned_to): self
    {
        $this->assigned_to = $assigned_to;

        return $this;
    }
}
