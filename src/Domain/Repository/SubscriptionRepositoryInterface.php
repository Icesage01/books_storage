<?php

namespace src\Domain\Repository;

/**
 * @template T of object
 */
interface SubscriptionRepositoryInterface extends ReadRepositoryInterface, WriteRepositoryInterface, SearchRepositoryInterface
{
    /**
     * @param int $bookId
     * @return array
     */
    public function findActiveSubscriptionsForBook(int $bookId): array;
}
