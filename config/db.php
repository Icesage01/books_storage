<?php

use src\Infrastructure\Config\DatabaseConfigFactory;

try {
    // Пытаемся использовать фабрику конфигурации
    $config = DatabaseConfigFactory::getConfig();
} catch (Exception $e) {
    // Fallback на локальную конфигурацию
    $config = [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=yii2basic',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
}

// Дополнительные настройки для разработки
if (defined('YII_ENV_DEV') && YII_ENV_DEV) {
    $config['enableSchemaCache'] = false;
    $config['enableQueryCache'] = false;
    $config['enableLogging'] = true;
    $config['enableProfiling'] = true;
}

return $config;