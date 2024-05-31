<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\FineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FineRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
        ),
    ]
)]
class Fine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $reason = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(length: 12)]
    private ?string $id_taxes = null;

    #[ORM\OneToOne(mappedBy: 'fine', cascade: ['persist', 'remove'])]
    private ?Paidment $paidment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getIdTaxes(): ?string
    {
        return $this->id_taxes;
    }

    public function setIdTaxes(string $id_taxes): static
    {
        $this->id_taxes = $id_taxes;

        return $this;
    }

    public function getPaidment(): ?Paidment
    {
        return $this->paidment;
    }

    public function setPaidment(?Paidment $paidment): static
    {
        // unset the owning side of the relation if necessary
        if ($paidment === null && $this->paidment !== null) {
            $this->paidment->setFine(null);
        }

        // set the owning side of the relation if necessary
        if ($paidment !== null && $paidment->getFine() !== $this) {
            $paidment->setFine($this);
        }

        $this->paidment = $paidment;

        return $this;
    }
}
