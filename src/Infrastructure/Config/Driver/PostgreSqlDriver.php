<?php

namespace src\Infrastructure\Config\Driver;

use PDO;
use PDOException;
use src\Infrastructure\Environment\EnvWithDefault;

class PostgreSqlDriver implements DatabaseDriverInterface
{
    protected string $host;
    protected string $port;
    protected string $database;
    protected string $username;
    protected string $password;
    protected string $schema;

    public function __construct()
    {
        $this->host = (new EnvWithDefault('DB_HOST', 'localhost'))->string();
        $this->port = (new EnvWithDefault('DB_PORT', '5432'))->string();
        $this->database = (new EnvWithDefault('DB_NAME', 'books_catalog'))->string();
        $this->username = (new EnvWithDefault('DB_USER', 'postgres'))->string();
        $this->password = (new EnvWithDefault('DB_PASSWORD', ''))->string();
        $this->schema = (new EnvWithDefault('DB_SCHEMA', 'public'))->string();
    }

    public function getConfig(): array
    {
        $isDebug = defined('YII_DEBUG') ? YII_DEBUG : true;
        
        return [
            'class' => 'yii\db\Connection',
            'dsn' => $this->getDsn(),
            'username' => $this->username,
            'password' => $this->password,
            'enableSchemaCache' => !$isDebug,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
            'enableQueryCache' => !$isDebug,
            'queryCacheDuration' => 1800,
            'queryCache' => 'cache',
            'attributes' => $this->getPdoAttributes(),
            'on afterOpen' => $this->getAfterOpenCallback(),
        ];
    }

    public function isAvailable(): bool
    {
        return $this->testConnection();
    }

    public function getDsn(): string
    {
        return sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $this->host,
            $this->port,
            $this->database
        );
    }

    public function getDriverName(): string
    {
        return 'PostgreSQL';
    }

    public function getConnectionParams(): array
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'username' => $this->username,
            'schema' => $this->schema,
        ];
    }

    public function testConnection(): bool
    {
        try {
            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', 
                $this->host, 
                $this->port, 
                $this->database
            );
            
            $pdo = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);
            
            return $pdo->query('SELECT 1') !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    protected function getPdoAttributes(): array
    {
        return [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
    }

    protected function getAfterOpenCallback(): callable
    {
        return function ($event) {
            $event->sender->createCommand(sprintf(
                'SET search_path TO %s',
                $this->schema
            ))->execute();
            
            $event->sender->createCommand('SET timezone = \'UTC\'')->execute();
        };
    }
}
