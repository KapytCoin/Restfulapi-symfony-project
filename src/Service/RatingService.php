<?php

namespace App\Service;

use App\Repository\ReviewRepository;

class RatingService
{
    public function __construct(private ReviewRepository $reviewRepository)
    {
    }

    public function calcReviewRatingForProduct(int $id, int $total): float
    {
        return $total > 0 ? $this->reviewRepository->getProductTotalRatingSum($id) / $total : 0;
    }
}