<?php

namespace App\Service;

use App\Model\Editor\CreateProductRequest;
use App\Model\Editor\ProductListItem;
use App\Model\Editor\ProductListResponse;
use App\Model\IdResponse;
use App\Model\UploadImageResponse;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Product;
use App\Exception\ProductAlreadyExistsException;
use DateTimeInterface;
use App\Model\PublishProductRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditorService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private SluggerInterface $slugger,
        private Security $security,
        private UploadService $uploadService)
    {
    }

    public function getProducts(): ProductListResponse
    {
        return new ProductListResponse(
            array_map([$this, 'map'], 
            $this->productRepository->findUserProducts($this->security->getUser()))
        );
    }

    // public function uploadImage(int $id, UploadedFile $file): UploadImageResponse
    // {
    //     $product = $this->productRepository->getUserProductById($id, $this->security->getUser());
    //     if (null !== $product->getImage()) {
    //         $this->uploadService->deleteProductFile($product->getId(), basename($product->getImage()));
    //     }

    //     $link = $this->uploadService->uploadProductFile($id, $file);

    //     $product->setImage($link);

    //     $this->em->flush();

    //     return new UploadImageResponse($link);
    // }

    public function createProduct(CreateProductRequest $request): IdResponse
    {
        $slug = $this->slugger->slug($request->getTitle());
        if ($this->productRepository->existsBySlug($slug)) {
            throw new ProductAlreadyExistsException();
        }

        $product = (new Product())
            ->setTitle($request->getTitle())
            ->setSlug($slug);

        $this->em->persist($product);
        $this->em->flush();

        return new IdResponse($product->getId());
    }

    public function deleteProductFile(int $id): void
    {
        $product = $this->productRepository->getUserProductById($id, $this->security->getUser());

        $this->em->remove($product);
        $this->em->flush();
    }

    private function map(Product $product): ProductListItem
    {
        return (new ProductListItem())
            ->setId($product->getId())
            ->setSlug($product->getSlug())
            ->setImage($product->getImage())
            ->setTitle($product->getTitle());
    }
}