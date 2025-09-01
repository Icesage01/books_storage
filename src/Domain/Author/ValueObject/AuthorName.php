<?php

namespace src\Domain\Author\ValueObject;

use InvalidArgumentException;

class AuthorName
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private ?string $middleName = null
    ) {
        $this->validate();
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

    public function getFullName(): string
    {
        $fullName = sprintf('%s %s', $this->lastName, $this->firstName);
        
        if (!is_null($this->middleName) && !empty($this->middleName)) {
            $fullName = sprintf('%s %s', $fullName, $this->middleName);
        }
        
        return $fullName;
    }

    public function getShortName(): string
    {
        return sprintf('%s %s.', $this->lastName, mb_substr($this->firstName, 0, 1));
    }

    private function validate(): void
    {
        if (empty($this->firstName)) {
            throw new InvalidArgumentException('Имя автора не может быть пустым');
        }

        if (empty($this->lastName)) {
            throw new InvalidArgumentException('Фамилия автора не может быть пустой');
        }

        if (mb_strlen($this->firstName) > 100) {
            throw new InvalidArgumentException('Имя автора не может быть длиннее 100 символов');
        }

        if (mb_strlen($this->lastName) > 100) {
            throw new InvalidArgumentException('Фамилия автора не может быть длиннее 100 символов');
        }

        if (!is_null($this->middleName) && mb_strlen($this->middleName) > 100) {
            throw new InvalidArgumentException('Отчество автора не может быть длиннее 100 символов');
        }
    }

    public function equals(AuthorName $other): bool
    {
        return $this->firstName === $other->firstName &&
               $this->lastName === $other->lastName &&
               $this->middleName === $other->middleName;
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }
}
