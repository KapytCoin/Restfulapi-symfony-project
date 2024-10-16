<?php

namespace App\Service;

use App\Model\Editor\CreateProductRequest;
use App\Model\Editor\ProductListItem;
use App\Model\Editor\ProductListResponse;
use App\Model\IdResponse;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Product;
use App\Exception\ProductAlreadyExistsException;

class EditorService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private SluggerInterface $slugger,
        private Security $security)
    {
    }

    public function getProducts(): ProductListResponse
    {
        return new ProductListResponse(
            array_map([$this, 'map'], 
            $this->productRepository->findUserProducts($this->security->getUser()))
        );
    }

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

    public function deleteProduct(int $id): void
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