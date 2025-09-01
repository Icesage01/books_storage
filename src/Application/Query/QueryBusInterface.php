<?php

namespace src\Application\Query;

interface QueryBusInterface
{
    /**
     * @param QueryInterface $query
     * @return mixed
     */
    public function dispatch(QueryInterface $query): mixed;
}
