<?php

namespace src\Application\Query\Handler;

use src\Application\Query\QueryInterface;
use src\Application\Query\GetTopAuthorsQuery;
use src\Domain\Repository\AuthorRepositoryInterface;

class GetTopAuthorsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AuthorRepositoryInterface $authorRepository
    ) {}

    public function handle(QueryInterface $query): array
    {
        if (!$query instanceof GetTopAuthorsQuery) {
            throw new \InvalidArgumentException('Неверный тип запроса');
        }

        $authorList = $this->authorRepository->findPopularAuthorsForReport($query->limit, $query->year);
        
        $result = [];
        foreach ($authorList as $author) {
            $result[] = [
                'id' => $author['id'],
                'firstName' => $author['firstName'],
                'lastName' => $author['lastName'],
                'middleName' => $author['middleName'],
                'bookCount' => $author['bookCount'] ?? 0,
                'fullName' => sprintf('%s %s', $author['firstName'], $author['lastName']),
                'shortName' => sprintf('%s %s', $author['firstName'], $author['lastName']),
            ];
        }
        
        return $result;
    }
}
