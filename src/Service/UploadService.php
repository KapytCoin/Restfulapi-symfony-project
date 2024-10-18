<?php

namespace App\Service;

use App\Exception\UploadFileInvalidTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class UploadService
{
    private const LINK_PRODUCT_PATTERN = '/upload/product/%d/%s';

    public function __construct(private string $uploadDir)
    {
    }

    public function uploadProductFile(int $productId, UploadedFile $file): string
    {
        $extension = $file->guessExtension();
        if (null === $extension) {
            throw new UploadFileInvalidTypeException();
        }

        $unigueName = Uuid::v4()->toRfc4122().'.'.$extension;
        $uploadPath = $this->uploadDir.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.$productId;

        $file->move($uploadPath, $unigueName);

        return sprintf(self::LINK_PRODUCT_PATTERN, $productId, $unigueName);
    }
}