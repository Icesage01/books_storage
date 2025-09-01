<?php

namespace src\Domain\Repository;

/**
 * @template T of object
 */
interface ReadRepositoryInterface
{
    /**
     * @param int $id
     * @return T|null
     */
    public function findById(int $id): ?object;
    
    /**
     * @return T[]
     */
    public function findAll(): array;
    
    /**
     * @param array $criteria
     * @return T[]
     */
    public function findBy(array $criteria): array;
    
    /**
     * @param array $criteria
     * @return T|null
     */
    public function findOneBy(array $criteria): ?object;
    
    /**
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria = []): int;
}
