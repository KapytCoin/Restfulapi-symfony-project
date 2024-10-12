<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class RatingService
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    public function calcReviewRatingForProduct(int $id): Rating
    {
        $total = $this->reviewRepository->countByProductId($id);
        $rating = $total > 0 ? $this->reviewRepository->getProductTotalRatingSum($id) / $total : 0;

        return new Rating($total, $rating);
    }
}