<?php

namespace src\Application\Command;

use src\Application\Command\CommandInterface;

class CreateBookCommand implements CommandInterface
{
    public function __construct(
        public readonly string $title,
        public readonly int $publicationYear,
        public readonly ?string $description,
        public readonly string $isbn,
        public readonly ?string $coverImage,
        public readonly array $authorIdList
    ) {}
}
