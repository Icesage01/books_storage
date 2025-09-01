<?php

namespace src\Infrastructure\Queue;

use src\Infrastructure\Environment\EnvWithDefault;
use Predis\Client;
use Yii;

class RedisQueue implements QueueInterface
{
    private Client $redis;

    public function __construct()
    {
        $host = (new EnvWithDefault('REDIS_HOST', 'localhost'))->string();
        $port = (new EnvWithDefault('REDIS_PORT', '6379'))->int();
        $database = (new EnvWithDefault('REDIS_DATABASE', '0'))->int();
        $password = (new EnvWithDefault('REDIS_PASSWORD', ''))->string();

        $this->redis = new Client([
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'password' => $password ?: null,
        ]);
    }

    public function push(string $queueName, array $data): bool
    {
        try {
            $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            if ($jsonData === false) {
                Yii::error(sprintf('Ошибка кодирования данных для очереди %s', $queueName));
                return false;
            }

            $this->redis->lpush($queueName, $jsonData);
            Yii::info(sprintf('Данные добавлены в очередь %s', $queueName));
            return true;
        } catch (\Exception $e) {
            Yii::error(sprintf('Ошибка добавления в очередь %s: %s', $queueName, $e->getMessage()));
            return false;
        }
    }

    public function pop(string $queueName): ?array
    {
        try {
            $data = $this->redis->rpop($queueName);
            
            if (is_null($data)) {
                return null;
            }

            $decodedData = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Yii::error(sprintf('Ошибка декодирования данных из очереди %s', $queueName));
                return null;
            }

            return $decodedData;
        } catch (\Exception $e) {
            Yii::error(sprintf('Ошибка получения данных из очереди %s: %s', $queueName, $e->getMessage()));
            return null;
        }
    }

    public function size(string $queueName): int
    {
        try {
            return $this->redis->llen($queueName);
        } catch (\Exception $e) {
            Yii::error(sprintf('Ошибка получения размера очереди %s: %s', $queueName, $e->getMessage()));
            return 0;
        }
    }
}
