<?php

namespace App\Repository;

use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param int $id
     * @return Product[]
     */
    public function findProductsByCategoryId(int $id): array
    {
        $query = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Product p WHERE :categoryId MEMBER OF p.categories');
        $query->setParameter("categoryId", $id);

        return $query->getResult();
    }

    public function getById(int $id): Product
    {
        $product = $this->find($id);
        if (null === $product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findProductsByIds(array $ids): array
    {
        return $this->fendBy(['id => $ids']);
    }
}
