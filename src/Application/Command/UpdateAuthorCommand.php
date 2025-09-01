<?php

namespace src\Application\Command;

use src\Application\Command\CommandInterface;
use src\Validation\AuthorValidation;

class UpdateAuthorCommand implements CommandInterface
{
    public function __construct(
        private readonly int $authorId,
        private readonly AuthorValidation $validation
    ) {}

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function getValidation(): AuthorValidation
    {
        return $this->validation;
    }
}
