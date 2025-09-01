<?php

namespace src\Application\Command;

use src\Application\Command\CommandInterface;

class DeleteBookCommand implements CommandInterface
{
    public function __construct(
        public readonly int $bookId
    ) {}
}
