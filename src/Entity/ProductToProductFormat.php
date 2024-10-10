<?php

namespace App\Entity;

use App\Repository\ProductToProductFormatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductToProductFormatRepository::class)]
class ProductToProductFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $price = null;

    #[ORM\Column]
    private ?int $discountPercent = null;

    #[ORM\ManyToOne(inversedBy: 'productToProductFormats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'productToProductFormats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductFormat $format = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getFormat(): ?ProductFormat
    {
        return $this->format;
    }

    public function setFormat(?ProductFormat $format): static
    {
        $this->format = $format;

        return $this;
    }
}
