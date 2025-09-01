<?php

namespace src\Infrastructure\Config\Driver;

interface DatabaseDriverInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getConfig(): array;

    /**
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * @return string
     */
    public function getDsn(): string;

    /**
     * @return string
     */
    public function getDriverName(): string;

    /**
     * @return array<string, string>
     */
    public function getConnectionParams(): array;

    /**
     * @return bool
     */
    public function testConnection(): bool;
}
