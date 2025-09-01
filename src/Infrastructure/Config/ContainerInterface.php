<?php

namespace src\Infrastructure\Config;

use InvalidArgumentException;

interface ContainerInterface
{
    /**
     * @param string $id
     * @return object
     * @throws InvalidArgumentException
     */
    public function get(string $id): object;
    
    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool;
    
    /**
     * @param string $id
     * @param callable|string $definition
     * @return void
     */
    public function set(string $id, $definition): void;
    
    /**
     * @param string $id
     * @param callable|string $definition
     * @return void
     */
    public function setSingleton(string $id, $definition): void;
}
