<?php

namespace App\Mapper;

use App\Entity\Product;
use App\Model\ProductDetails;
use App\Model\ProductListItem;
use App\Model\RecommendedProduct;

class ProductMapper
{
    public static function map(Product $product, ProductDetails|ProductListItem $model): ProductDetails|ProductListItem
    {
        return $model
        ->setId($product->getId())
        ->setTitle($product->getTitle())
        ->setSlug($product->getSlug())
        ->setImage($product->getImage())
        ->setPublicationDate($product->getPublicationDate()->getTimestamp());
    }

    public static function mapRecommended(Product $product): RecommendedProduct
    {
        $description = $product->getDescription();
        $description = strlen($description) > 150 ? substr($description, 0, 150). '...' : $description;

        return (new RecommendedProduct())
            ->setId($product->getId())
            ->setImage($product->getImage())
            ->setSlug($product->getSlug())
            ->setTitle($product->getTitle())
            ->setShortDescription($description);
    }
}