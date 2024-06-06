<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\FineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FineRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
        ),
        new GetCollection(),
        new Post(
            uriTemplate: '/fines/byIdTaxes',
            routeName: 'searchByIdTaxes',
            // requirements: ['idTaxe']
        )
    ]
)]
class Fine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 10)]
    #[Assert\Regex(
        "/^[A-Z]{2}\d{4}_\d{1,2}_\d{1,2}$/", 
        message: "The characters entered are not correct.")]
    #[ORM\Column(length: 100)]
    private string $reason = "";

    #[Assert\Length(min: 10)]
    #[Assert\Regex(
        "/^\d+(\.\d{1,2})?$/", 
        message: "The characters entered are not correct.")]
    #[ORM\Column]
    private int $amount = 0;

    #[Assert\Length(min: 10)]
    #[Assert\Regex(
        "/^[A-Z]{2}\d{4}_\d{1,2}_\d{1,2}$/", 
        message: "The characters entered are not correct.")]
    #[ORM\Column(length: 12)]
    private string $id_taxes = "";

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

    public function __toString()
    {
        return $this->id_taxes;
    }
}
