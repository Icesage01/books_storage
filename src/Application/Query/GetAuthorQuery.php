<?php

namespace src\Application\Query;

class GetAuthorQuery implements QueryInterface
{
    public function __construct(
        private int $authorId
    ) {
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }
}
