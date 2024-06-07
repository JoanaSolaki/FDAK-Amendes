<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Repository\PaidmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaidmentRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(),
        new Post(
            uriTemplate: '/paidments/check',
            routeName: 'post_fine',
        ),
    ]
)]
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
    
    #[Assert\Length(min: 12)]
    #[ORM\Column(length: 20)]
    private string $card = "";

    #[Assert\Length(min: 3)]
    #[Assert\Regex(
        "/^\d{3,4}$/", 
        message: "The characters entered are not correct.")]
    #[ORM\Column(length: 4)]
    private string $crypto = "";

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull]
    #[Assert\GreaterThan("today", message: "Carte périmée.")]
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
