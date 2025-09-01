<?php

namespace src\Domain\Event\Subscription;

use DateTimeImmutable;
use src\Domain\Event\DomainEventInterface;

class SubscriptionActivated implements DomainEventInterface
{
    public function __construct(
        private readonly int $subscriptionId,
        private readonly int $userId,
        private readonly int $authorId,
        private readonly string $userPhone,
        private readonly string $authorName,
        private readonly DateTimeImmutable $occurredOn
    ) {}

    public function getSubscriptionId(): int
    {
        return $this->subscriptionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getUserPhone(): string
    {
        return $this->userPhone;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getEventName(): string
    {
        return 'subscription.activated';
    }

    public function getAggregateId(): string
    {
        return (string) $this->subscriptionId;
    }
}
