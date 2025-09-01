<?php

namespace src\Application\Service;

use src\Domain\Repository\ReadRepositoryInterface;
use src\Domain\Repository\SearchRepositoryInterface;
use src\Infrastructure\Database\Pagination\PaginationHelper;

class PaginationService
{
    private PaginationHelper $paginationHelper;
    
    public function __construct(ReadRepositoryInterface&SearchRepositoryInterface $repository, int $pageSize = 20)
    {
        $this->paginationHelper = new PaginationHelper($repository, $pageSize);
    }
    
    public function getPage(int $page, array $criteria = [], array $orderBy = []): array
    {
        return $this->paginationHelper->getPage($page, $criteria, $orderBy);
    }
    
    public function getPaginationInfo(int $page, array $criteria = []): array
    {
        return $this->paginationHelper->getPaginationInfo($page, $criteria);
    }
    
    public function getTotalCount(array $criteria = []): int
    {
        return $this->paginationHelper->getTotalCount($criteria);
    }
    
    public function getTotalPages(array $criteria = []): int
    {
        return $this->paginationHelper->getTotalPages($criteria);
    }
}
