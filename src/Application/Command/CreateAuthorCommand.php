<?php

namespace src\Application\Command;

use src\Validation\AuthorValidation;

class CreateAuthorCommand implements CommandInterface
{
    public function __construct(
        private AuthorValidation $validation
    ) {
    }

    public function getValidation(): AuthorValidation
    {
        return $this->validation;
    }
}
