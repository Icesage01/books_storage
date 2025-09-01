<?php

namespace src\Domain\Repository;

/**
 * @template T of object
 */
interface SearchRepositoryInterface
{
    /**
     * @param array $criteria Критерии фильтрации
     * @param array $orderBy Сортировка ['field' => SORT_ASC/SORT_DESC]
     * @param int|null $limit Лимит записей
     * @param int|null $offset Смещение для пагинации
     * @return T[]
     */
    public function findWithOptions(
        array $criteria = [],
        array $orderBy = [],
        ?int $limit = null,
        ?int $offset = null
    ): array;
}
