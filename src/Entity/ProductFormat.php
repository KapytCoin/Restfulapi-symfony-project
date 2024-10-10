<?php

namespace App\Entity;

use App\Repository\ProductFormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductFormatRepository::class)]
class ProductFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?int $discountPercent = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $price = null;

    /**
     * @var Collection<int, ProductToProductFormat>
     */
    #[ORM\OneToMany(targetEntity: ProductToProductFormat::class, mappedBy: 'productFormat')]
    private Collection $productToProductFormats;

    public function __construct()
    {
        $this->productToProductFormats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getDiscountPercent(): ?int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(int $discountPercent): static
    {
        $this->discountPercent = $discountPercent;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, ProductToProductFormat>
     */
    public function getProductToProductFormats(): Collection
    {
        return $this->productToProductFormats;
    }

    public function addProductToProductFormat(ProductToProductFormat $productToProductFormat): static
    {
        if (!$this->productToProductFormats->contains($productToProductFormat)) {
            $this->productToProductFormats->add($productToProductFormat);
            $productToProductFormat->setFormat($this);
        }

        return $this;
    }

    public function removeProductToProductFormat(ProductToProductFormat $productToProductFormat): static
    {
        if ($this->productToProductFormats->removeElement($productToProductFormat)) {
            // set the owning side to null (unless already changed)
            if ($productToProductFormat->getFormat() === $this) {
                $productToProductFormat->setFormat(null);
            }
        }

        return $this;
    }
}
