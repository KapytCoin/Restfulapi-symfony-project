<?php

namespace App\Controller;

use App\Model\SubscriberRequest;
use App\Service\SubscriberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;
use App\Attribute\RequestBody;

class SubscriberController extends AbstractController
{
    public function __construct(private SubscriberService $subscriberService)
    {
    }

    /**
     * @OA\Response(
     *      response=200,
     *      description="Subscribe email to newsletter mailing list",
     * )
     * @OA\Response(
     *      response="400",
     *      description="Validation failed",
     *      @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(@Model(type=SubscriberRequest::class))
     */
    #[Route(path: '/api/v1/subscribe', methods: ['POST'])]
    public function action(#[RequestBody] SubscriberRequest $subscriberRequest): Response
    {
        $this->subscriberService->subscribe($subscriberRequest);

        return $this->json(null);
    }
}