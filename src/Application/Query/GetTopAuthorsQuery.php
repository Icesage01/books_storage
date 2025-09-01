<?php

namespace src\Application\Query;

class GetTopAuthorsQuery implements QueryInterface
{
    public function __construct(
        public readonly int $limit = 10,
        public readonly ?int $year = null
    ) {}
}
