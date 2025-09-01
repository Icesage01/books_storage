<?php

namespace src\Application\Command;

use src\Application\Command\CommandInterface;

class DeleteAuthorCommand implements CommandInterface
{
    public function __construct(
        private readonly int $authorId
    ) {}

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}
