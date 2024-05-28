<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaidmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaidmentRepository::class)]
#[ApiResource]
class Paidment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'paidments')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 20)]
    private ?string $card = null;

    #[ORM\Column(length: 10)]
    private ?string $crypto = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $exp_date = null;

    #[ORM\OneToOne(inversedBy: 'paidment', cascade: ['persist', 'remove'])]
    private ?Fine $fine = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCard(): ?string
    {
        return $this->card;
    }

    public function setCard(string $card): static
    {
        $this->card = $card;

        return $this;
    }

    public function getCrypto(): ?string
    {
        return $this->crypto;
    }

    public function setCrypto(string $crypto): static
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getExpDate(): ?\DateTimeInterface
    {
        return $this->exp_date;
    }

    public function setExpDate(\DateTimeInterface $exp_date): static
    {
        $this->exp_date = $exp_date;

        return $this;
    }

    public function getFine(): ?Fine
    {
        return $this->fine;
    }

    public function setFine(?Fine $fine): static
    {
        $this->fine = $fine;

        return $this;
    }
}
