<?php

namespace App\Exception;

class ProductNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('product not found');
    }
}