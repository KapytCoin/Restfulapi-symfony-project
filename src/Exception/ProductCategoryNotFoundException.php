<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("product category not found");
    }
}