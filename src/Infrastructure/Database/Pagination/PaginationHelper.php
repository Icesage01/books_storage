<?php

namespace src\Infrastructure\Database\Pagination;

use src\Domain\Repository\ReadRepositoryInterface;
use src\Domain\Repository\SearchRepositoryInterface;

class PaginationHelper
{
    private ReadRepositoryInterface&SearchRepositoryInterface $repository;
    private int $pageSize;
    
    public function __construct(ReadRepositoryInterface&SearchRepositoryInterface $repository, int $pageSize = 20)
    {
        $this->repository = $repository;
        $this->pageSize = $pageSize;
    }
    
    /**
     * @param int $page
     * @param array $criteria
     * @param array $orderBy
     * @return array
     */
    public function getPage(int $page, array $criteria = [], array $orderBy = []): array
    {
        $offset = ($page - 1) * $this->pageSize;
        
        return $this->repository->findWithOptions(
            criteria: $criteria,
            orderBy: $orderBy,
            limit: $this->pageSize,
            offset: $offset
        );
    }
    
    /**
     * @param array $criteria
     * @return int
     */
    public function getTotalPages(array $criteria = []): int
    {
        $totalCount = $this->repository->count($criteria);
        return (int) ceil($totalCount / $this->pageSize);
    }
    
    /**
     * @param array $criteria Критерии фильтрации
     * @return int
     */
    public function getTotalCount(array $criteria = []): int
    {
        return $this->repository->count($criteria);
    }
    
    /**
     * @param int $page
     * @param array $criteria
     * @return array
     */
    public function getPaginationInfo(int $page, array $criteria = []): array
    {
        $totalCount = $this->getTotalCount($criteria);
        $totalPages = $this->getTotalPages($criteria);
        
        return [
            'currentPage' => $page,
            'pageSize' => $this->pageSize,
            'totalCount' => $totalCount,
            'totalPages' => $totalPages,
            'hasNextPage' => $page < $totalPages,
            'hasPrevPage' => $page > 1,
            'nextPage' => $page < $totalPages ? $page + 1 : null,
            'prevPage' => $page > 1 ? $page - 1 : null,
        ];
    }
}
