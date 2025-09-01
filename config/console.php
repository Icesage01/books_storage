<?php

use src\Infrastructure\Environment\EnvironmentLoader;
use src\Infrastructure\Config\DatabaseConfigFactory;
use src\Infrastructure\Environment\Env;

EnvironmentLoader::load();

$dbDriverEnv = new Env('DB_DRIVER');
$driverType = strtolower($dbDriverEnv->value());

if (!empty($driverType) && !DatabaseConfigFactory::isDriverSupported($driverType)) {
    echo sprintf(
        "ОШИБКА: Неподдерживаемый драйвер БД '%s'. Поддерживаемые: %s\n",
        $driverType,
        implode(', ', DatabaseConfigFactory::getSupportedDrivers())
    );
    exit(1);
}

$db = DatabaseConfigFactory::getConfig();

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'container'],
    'controllerNamespace' => 'console',
    'aliases' => [
        '@tests' => '@app/tests',
        '@src' => '@app/src',
        '@domain' => '@src/Domain',
        '@application' => '@src/Application',
        '@infrastructure' => '@src/Infrastructure',
        '@presentation' => '@src/Presentation',
        '@console' => '@app/console',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@app/src/Infrastructure/Database/Migration',
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'container' => [
            'class' => 'src\Infrastructure\Config\ContainerManager',
        ],
        'queue' => [
            'class' => 'src\Infrastructure\Queue\RedisQueue',
        ],
        'smsQueueService' => [
            'class' => 'src\Infrastructure\Queue\SmsQueueService',
        ],
    ],
    'params' => [
        'dbDriver' => $driverType,
        'dbSupported' => DatabaseConfigFactory::isDriverSupported($driverType),
    ],
];

return $config;
