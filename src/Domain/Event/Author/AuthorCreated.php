<?php

namespace src\Domain\Event\Author;

use DateTimeImmutable;
use src\Domain\Event\DomainEventInterface;

class AuthorCreated implements DomainEventInterface
{
    public function __construct(
        private readonly int $authorId,
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly ?string $middleName,
        private readonly ?string $biography,
        private readonly DateTimeImmutable $occurredOn
    ) {}

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function getFullName(): string
    {
        $fullName = sprintf('%s %s', $this->lastName, $this->firstName);
        
        if (!is_null($this->middleName) && !empty($this->middleName)) {
            $fullName = sprintf('%s %s', $fullName, $this->middleName);
        }
        
        return $fullName;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getEventName(): string
    {
        return 'author.created';
    }

    public function getAggregateId(): string
    {
        return (string) $this->authorId;
    }
}
