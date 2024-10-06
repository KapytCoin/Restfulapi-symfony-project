<?php

namespace App\Model;

class ProductListResponse
{
    /**
     * @var \App\Model\ProductListItem[]
     */
    private array $items;

    /**
     * @param \App\Model\ProductListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return \App\Model\ProductListItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}