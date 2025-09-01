<?php

namespace src\Domain\Repository;

/**
 * @template T of object
 */
interface AuthorRepositoryInterface extends ReadRepositoryInterface, WriteRepositoryInterface, SearchRepositoryInterface
{
    /**
     * @param int $limit
     * @param int|null $year
     * @return array
     */
    public function findPopularAuthors(int $limit = 10, ?int $year = null): array;

    /**
     * @param int $limit
     * @param int|null $year
     * @return array
     */
    public function findPopularAuthorsForReport(int $limit = 10, ?int $year = null): array;
}
