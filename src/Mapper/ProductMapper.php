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
}