<?php

namespace src\Infrastructure\Queue;

interface QueueInterface
{
    /**
     * @param string $queueName
     * @param array $data
     * @return bool
     */
    public function push(string $queueName, array $data): bool;

    /**
     * @param string $queueName
     * @return array|null
     */
    public function pop(string $queueName): ?array;

    /**
     * @param string $queueName
     * @return int
     */
    public function size(string $queueName): int;
}
