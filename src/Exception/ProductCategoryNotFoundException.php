<?php

namespace App\Exception;

use RuntimeException;

class ProductCategoryNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("product category not found");
    }
}