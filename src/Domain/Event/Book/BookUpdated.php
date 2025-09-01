<?php

namespace src\Domain\Event\Book;

use DateTimeImmutable;
use src\Domain\Event\DomainEventInterface;

class BookUpdated implements DomainEventInterface
{
    public function __construct(
        private readonly int $bookId,
        private readonly string $title,
        private readonly int $publicationYear,
        private readonly DateTimeImmutable $occurredOn
    ) {}

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPublicationYear(): int
    {
        return $this->publicationYear;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getEventName(): string
    {
        return 'book.updated';
    }

    public function getAggregateId(): string
    {
        return (string) $this->bookId;
    }
}
