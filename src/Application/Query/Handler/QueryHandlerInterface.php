<?php

namespace src\Application\Query\Handler;

use src\Application\Query\QueryInterface;

interface QueryHandlerInterface
{
    /**
     * @param QueryInterface $query
     * @return mixed
     */
    public function handle(QueryInterface $query): mixed;
}
