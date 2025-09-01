<?php

declare(strict_types=1);

namespace src\Infrastructure\Environment;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class EnvironmentLoader
{
    private static bool $isLoaded = false;
    
    public static function load(?string $path = null): void
    {
        if (self::$isLoaded) {
            return;
        }
        
        $path = $path ?? dirname(__DIR__, 3);
        
        try {
            $dotenv = Dotenv::createImmutable($path);
            $dotenv->load();
            self::$isLoaded = true;
        } catch (InvalidPathException $e) {
            self::$isLoaded = true;
        }
    }
    
    public static function isLoaded(): bool
    {
        return self::$isLoaded;
    }
}
