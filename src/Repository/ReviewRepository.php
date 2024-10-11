<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function countByProductId(int $id): int
    {
        return $this->count(['product' => $id]);
    }

    public function getProductTotalRatingSum(int $id): int
    {
        return (int) $this->getEntityManager()->createQuery('SELECT SUM(r.rating) FROM App\Entity\Review r WHERE r.product = :id')
            ->setParameter('id', $id)
            ->getSingleScalarResult();
    }

    public function getPageByProductId(int $id, int $offset, int $limit): Paginator
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT r FROM App\Entity\Review r WHERE r.product = :id ORDER BY r.createdAt DESC')
            ->setParameter('id', $id)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($query, false);
    }

//    /**
//     * @return Review[] Returns an array of Review objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Review
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
