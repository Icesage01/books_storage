<?php

namespace src\Application\Query\Handler;

use InvalidArgumentException;
use src\Application\Query\QueryInterface;
use src\Application\Query\GetAuthorQuery;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Author\AuthorModel;

class GetAuthorQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function handle(QueryInterface $query): ?AuthorModel
    {
        if (!$query instanceof GetAuthorQuery) {
            throw new InvalidArgumentException('Неверный тип запроса');
        }

        return $this->authorRepository->findById($query->getAuthorId());
    }
}
