<?php

namespace src\Application\Command;

use src\Application\Command\CommandInterface;
use src\Validation\BookValidation;

class UpdateBookCommand implements CommandInterface
{
    public function __construct(
        public readonly int $bookId,
        public readonly array $authorIdList,
        private readonly BookValidation $validation,
    ) {}

    public function getValidation(): BookValidation
    {
        return $this->validation;
    }
}
