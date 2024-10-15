<?php

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

class CreateProductRequest
{
    #[NotBlank]
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}