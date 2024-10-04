<?php

namespace Src\Service;

use App\Repository\ProductCategoryRepository;
use Src\Model\ProductCategoryListResponse;
use App\Entity\ProductCategory;
use Src\Model\ProductCategoryListItem;
use Doctrine\Common\Collections\Criteria;

class ProductCategoryService
{
    public function __construct(private ProductCategoryRepository $productCategoryRepository)
    {
    }

    public function getCategories(): ProductCategoryListResponse
    {
        $categories = $this->productCategoryRepository->findBy([], ['title' => Criteria::ASC]);
        $items = array_map(
            fn (ProductCategory $productCategory) => new ProductCategoryListItem(
                $productCategory->getId(), $productCategory->getTitle(), $productCategory->getSlug()
            ),
            $categories
        );

        return new ProductCategoryListResponse($items);
    }
}