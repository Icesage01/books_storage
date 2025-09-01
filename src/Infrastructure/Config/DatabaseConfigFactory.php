<?php

namespace src\Infrastructure\Config;

use Exception;
use InvalidArgumentException;
use src\Infrastructure\Config\Driver\DatabaseDriverInterface;
use src\Infrastructure\Config\Driver\MySqlDriver;
use src\Infrastructure\Config\Driver\PostgreSqlDriver;
use src\Infrastructure\Environment\Env;

class DatabaseConfigFactory
{
    private const SUPPORTED_DRIVERS = [
        'mysql' => MySqlDriver::class,
        'postgresql' => PostgreSqlDriver::class,
        'pgsql' => PostgreSqlDriver::class,
    ];

    /**
     * @return DatabaseDriverInterface
     * @throws InvalidArgumentException
     */
    public static function createDriver(): DatabaseDriverInterface
    {
        $dbDriverEnv = new Env('DB_DRIVER');
        $driverType = strtolower($dbDriverEnv->value());
        
        if (empty($driverType)) {
            throw new InvalidArgumentException('DB_DRIVER не указан в переменных окружения');
        }
        
        if (!self::isDriverSupported($driverType)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Неподдерживаемый тип БД: %s. Поддерживаемые: %s', 
                    $driverType, 
                    implode(', ', self::getSupportedDrivers())
                )
            );
        }

        $driverClass = self::SUPPORTED_DRIVERS[$driverType];
        
        try {
            return new $driverClass();
        } catch (Exception $e) {
            throw new InvalidArgumentException(
                sprintf('Ошибка создания драйвера %s: %s', $driverClass, $e->getMessage())
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function getConfig(): array
    {
        $driver = self::createDriver();
        $config = $driver->getConfig();

        if (!isset($config['class'])) {
            throw new InvalidArgumentException('Конфигурация БД должна содержать класс подключения');
        }

        return $config;
    }

    /**
     * @return bool
     */
    public static function isDatabaseAvailable(): bool
    {
        try {
            $driver = self::createDriver();
            return $driver->isAvailable();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public static function getDriverInfo(): array
    {
        try {
            $driver = self::createDriver();
            $connectionParams = $driver->getConnectionParams();
            
            return [
                'name' => $driver->getDriverName(),
                'dsn' => $driver->getDsn(),
                'available' => $driver->isAvailable(),
                'connection_test' => $driver->testConnection(),
                'params' => $connectionParams,
            ];
        } catch (Exception $e) {
            return [
                'name' => 'Unknown',
                'dsn' => 'N/A',
                'available' => false,
                'connection_test' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array<string>
     */
    public static function getSupportedDrivers(): array
    {
        return array_keys(self::SUPPORTED_DRIVERS);
    }

    /**
     * @param string $driverType
     * @return bool
     */
    public static function isDriverSupported(string $driverType): bool
    {
        return isset(self::SUPPORTED_DRIVERS[strtolower($driverType)]);
    }
}
