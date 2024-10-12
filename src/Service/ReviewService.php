<?php

namespace App\Service;

use App\Model\Review as ReviewModel;
use App\Entity\Review;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use App\Repository\ReviewRepository\RatingService;

class ReviewService
{
    private const PAGE_LIMIT = 5;

    public function __construct(private ReviewRepository $reviewRepository, private RatingService $ratingService)
    {
    }

    public function getReviewPageByProductId(int $id, int $page): ReviewPage
    {
        $offset = max($page - 1, 0) * self::PAGE_LIMIT;
        $paginator = $this->reviewRepository->getPageByProductId($id, $offset, self::PAGE_LIMIT);
        $ratingSum = $this->reviewRepository->getProductTotalRatingSum($id);
        $total = count($paginator);
        $rating = 0;

        if ($total > 0) {
            $rating = $this->reviewRepository->getProductTotalRatingSum($id) / $total;
        }

        return (new ReviewPage())
            ->setRating($this->ratingService->calcReviewRatingForProduct($id, $total))
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages(ceil($total / self::PAGE_LIMIT))
            ->setItems(array_map([$this, 'map'], $paginator->getIterator()->getArrayCopy()));
    }

    public function map(Review $review): ReviewModel
    {
        return (new ReviewModel())
            ->setId($review->getId())
            ->setRating($review->getRating())
            ->setCreatedAt($review->getCreatedAt()->getTimestamp())
            ->setAuthor($review->getAuthor())
            ->setContent($review->getContent());
    }
}