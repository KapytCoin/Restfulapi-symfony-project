<?php

namespace App\Exception;

use RuntimeException;

class ProductAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('product already exists');
    }
}