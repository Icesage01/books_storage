<?php

namespace src\Domain\Author;

use src\Models\AuthorModel as BaseAuthorModel;
use src\Domain\Author\ValueObject\AuthorName;

class AuthorModel extends BaseAuthorModel
{
    public function getAuthorName(): AuthorName
    {
        return new AuthorName(
            $this->firstName,
            $this->lastName,
            $this->middleName
        );
    }

    public function getFullName(): string
    {
        return $this->getAuthorName()->getFullName();
    }

    public function getShortName(): string
    {
        return $this->getAuthorName()->getShortName();
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
