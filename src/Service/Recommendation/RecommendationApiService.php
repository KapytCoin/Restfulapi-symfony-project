<?php

namespace App\Service\Recommendation;

use App\Service\Recommendation\Exception\RequestException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\Recommendation\Model\RecommendationResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Throwable;
use App\Service\Recommendation\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class RecommendationApiService
{
    public function __construct(private HttpClientInterface $recommendationClient, private SerializerInterface $serializer)
    {
    }

    public function getRecommendationsByProductId(int $productId): RecommendationResponse
    {
        try {
            $response = $this->recommendationClient->request('GET', '/api/v1/product/'.$productId.'/recommendations');

            return $this->serializer->deserialize(
                $response->getContent(),
                RecommendationResponse::class,
                JsonEncoder::FORMAT
            );
        } catch (Throwable $ex) {
            if ($ex instanceof TransportExceptionInterface && Response::HTTP_FORBIDDEN === $ex->getCode()) {
                throw new AccessDeniedException($ex);
            }

            throw new RequestException($ex->getMessage(), $ex);
        }
    }
}