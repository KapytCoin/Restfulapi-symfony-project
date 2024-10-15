<?php

namespace App\Repository;

use App\Entity\Product;
use App\Exception\ProductNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function findPublishedProductsByCategoryId(int $id): array
    {
        return $this->getEntityManager()
        ->createQuery('SELECT p FROM App\Entity\Product p WHERE :categoryId MEMBER OF p.categories AND p.publicationDate IS NOT NULL')
        ->setParameter('id', $id)
        ->getResult();
    }

    public function getPublishedById(int $id): Product
    {
        $product = $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Product p WHERE p.id = :id AND p.publicationDate IS NOT NULL')
        ->setParameter('id', $id)
        ->getOneOrNullResult();

        if (null === $product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findProductsByIds(array $ids): array
    {
        return $this->getEntityManager()->createQuery('SELECT p FROM App\Entity\Product p WHERE p.id IN (:ids) AND p.publicationDate IS NOT NULL')
            ->setParameter('ids', $ids)
            ->getResult();
    }

    public function findUserProducts(UserInterface $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function getUserProductById(int $id, UserInterface $user): Product
    {
        $product = $this->findOneBy(['id' => $id, 'user' => $user]);
        if (null === $product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function existsBySlug(string $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}
