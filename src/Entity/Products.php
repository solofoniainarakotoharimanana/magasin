<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\ProductsRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Products
{
    use Timestampable;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToMany(targetEntity: CommandDetail::class, mappedBy: 'product')]
    private Collection $commandDetails;

    public function __construct()
    {
        $this->commandDetails = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->slug = (new Slugify())->slugify($this->title);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, CommandDetail>
     */
    public function getCommandDetails(): Collection
    {
        return $this->commandDetails;
    }

    public function addCommandDetail(CommandDetail $commandDetail): static
    {
        if (!$this->commandDetails->contains($commandDetail)) {
            $this->commandDetails->add($commandDetail);
            $commandDetail->setProduct($this);
        }

        return $this;
    }

    public function removeCommandDetail(CommandDetail $commandDetail): static
    {
        if ($this->commandDetails->removeElement($commandDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandDetail->getProduct() === $this) {
                $commandDetail->setProduct(null);
            }
        }

        return $this;
    }
}
