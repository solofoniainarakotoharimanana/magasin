<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\CommandDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandDetailRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CommandDetail
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $command = null;

    #[ORM\Column]
    private ?int $quantityCmd = null;

    #[ORM\ManyToOne(inversedBy: 'commandDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $product = null;

    #[ORM\Column]
    private ?float $totalPrice = null;

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->totalPrice = $this->quantityCmd * $this->product->getPrice();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?Commande
    {
        return $this->command;
    }

    public function setCommand(?Commande $command): static
    {
        $this->command = $command;

        return $this;
    }

    public function getQuantityCmd(): ?int
    {
        return $this->quantityCmd;
    }

    public function setQuantityCmd(int $quantityCmd): static
    {
        $this->quantityCmd = $quantityCmd;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(?Products $product): static
    {
        $this->product = $product;

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
