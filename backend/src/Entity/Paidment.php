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
        new Post(
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
    #[Assert\Regex(
        '/(^4[0-9]{12}(?:[0-9]{3})?$)|(^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}$)|(3[47][0-9]{13})|(^3(?:0[0-5]|[68][0-9])[0-9]{11}$)|(^6(?:011|5[0-9]{2})[0-9]{12}$)|(^(?:2131|1800|35\d{3})\d{11}$)/', 
        message: "The characters entered are not correct.")]
    #[ORM\Column(length: 20)]
    private string $card = "";

    #[Assert\Length(min: 3)]
    #[Assert\Regex(
        "/^\d{3,4}$/", 
        message: "The characters entered are not correct.")]
    #[ORM\Column(length: 4)]
    private int $crypto = 0;

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
