<?php

namespace src\Domain\Repository;

/**
 * @template T of object
 */
interface WriteRepositoryInterface
{
    /**
     * Сохранить сущность
     * 
     * @param T $entity
     * @return bool
     */
    public function save(object $entity): bool;
    
    /**
     * Удалить сущность
     * 
     * @param T $entity
     * @return bool
     */
    public function delete(object $entity): bool;
}
