<?php

use src\Infrastructure\Environment\EnvironmentLoader;
use src\Infrastructure\Config\DatabaseConfigFactory;

EnvironmentLoader::load();

$dbConfig = null;
$dbAvailable = false;
$dbDriverType = '';
$dbSupported = false;

try {
    $dbDriverEnv = new \src\Infrastructure\Environment\Env('DB_DRIVER');
    $dbDriverType = strtolower($dbDriverEnv->value());
    $dbSupported = DatabaseConfigFactory::isDriverSupported($dbDriverType);
    
    if ($dbSupported) {
        $dbConfig = DatabaseConfigFactory::getConfig();
        $dbAvailable = DatabaseConfigFactory::isDatabaseAvailable();
    } else {
        error_log(sprintf('Неподдерживаемый драйвер БД: %s', $dbDriverType));
    }
} catch (Exception $e) {
    error_log(sprintf('Ошибка конфигурации БД: %s', $e->getMessage()));
}

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'src\Presentation\Controller',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@src' => '@app/src',
        '@domain' => '@src/Domain',
        '@application' => '@src/Application',
        '@infrastructure' => '@src/Infrastructure',
        '@presentation' => '@src/Presentation',
        '@views' => '@presentation/View',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'avwb3IzyhSZhioVVgH76nA77YV_6kYsu',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'src\Models\UserModel',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'defaultRoute' => 'site/index',
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'view' => [
            'class' => 'yii\web\View',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $dbConfig ?? [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2basic',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'smsService' => [
            'class' => 'src\Infrastructure\External\SmsPilot\SmsPilotService',
        ],
        'queue' => [
            'class' => 'src\Infrastructure\Queue\RedisQueue',
        ],
        'smsQueueService' => [
            'class' => 'src\Infrastructure\Queue\SmsQueueService',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<folder1:\w+>/<folder2:\w+>/<controller:\w+>/<action:\w+>' => '<folder1>/<folder2>/<controller>/<action>',
                '<folder1:\w+>/<controller:\w+>/<action:\w+>' => '<folder1>/<controller>/<action>',
                '' => 'site/index',
            ],
        ],
    ],
    'params' => [
        'dbAvailable' => $dbAvailable,
        'dbDriver' => $dbDriverType,
        'dbSupported' => $dbSupported,
    ],
];

return $config;
