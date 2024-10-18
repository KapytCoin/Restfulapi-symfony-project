<?php

namespace App\Model;

class UploadImageResponse
{
    public function __construct(private string $link)
    {
    }

    public function getLink(): string
    {
        return $this->link;
    }
}