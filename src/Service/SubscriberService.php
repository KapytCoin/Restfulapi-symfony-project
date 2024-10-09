<?php

namespace App\Service;
use App\Exception\SubscriberAlreadyExistsException;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Model\SubscriberRequest;
use App\Entity\Subscriber;

class SubscriberService
{
    public function __construct(private SubscriberRepository $subscriberRepository, private EntityManagerInterface $em)
    {
    }

    public function subscribe(SubscriberRequest $request): void
    {
        if ($this->subscriberRepository->existsByEmail($request->getEmail())) {
            throw new SubscriberAlreadyExistsException();
        }

        $subscriber = new Subscriber();
        $subscriber->setEmail($request->getEmail());

        $this->em->persist($subscriber);
        $this->em->flush();
    }
}