<?php

namespace App\Service;

use App\Model\PublishProductRequest;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ProductPublishService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private Security $security)
    {
    }

    public function publish(int $id, PublishProductRequest $publishProductRequest): void
    {
        $this->setPublicationDate($id, $publishProductRequest->getDate());
    }

    public function unpublish(int $id): void
    {
        $this->setPublicationDate($id, null);
    }

    private function setPublicationDate(int $id, ?DateTimeInterface $dateTime): void
    {
        $product = $this->productRepository->getUserProductById($id, $this->security->getUser());
        $product->setPublicationDate($dateTime);

        $this->em->flush();
    }
}