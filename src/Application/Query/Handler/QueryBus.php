<?php

namespace src\Application\Query\Handler;

use src\Application\Query\QueryInterface;
use src\Application\Query\QueryBusInterface;

class QueryBus implements QueryBusInterface
{
    /**
     * @var array<string, QueryHandlerInterface>
     */
    private array $handlers = [];

    public function register(string $queryClass, QueryHandlerInterface $handler): void
    {
        $this->handlers[$queryClass] = $handler;
    }

    public function dispatch(QueryInterface $query): mixed
    {
        $queryClass = get_class($query);
        
        if (!isset($this->handlers[$queryClass])) {
            throw new \RuntimeException(sprintf('Обработчик для запроса %s не найден', $queryClass));
        }

        return $this->handlers[$queryClass]->handle($query);
    }
}
