<?php

namespace src\Domain\Event\Book;

use DateTimeImmutable;
use src\Domain\Event\DomainEventInterface;

class BookCreated implements DomainEventInterface
{
    public function __construct(
        private readonly int $bookId,
        private readonly string $title,
        private readonly int $publicationYear,
        private readonly ?string $description,
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getEventName(): string
    {
        return 'book.created';
    }

    public function getAggregateId(): string
    {
        return (string) $this->bookId;
    }
}
