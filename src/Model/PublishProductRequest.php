<?php

namespace App\Model;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublishProductRequest
{
    #[NotBlank]
    private DateTimeInterface $date;

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}