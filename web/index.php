<?php

require __DIR__ . '/../vendor/autoload.php';

use src\Infrastructure\Environment\EnvWithDefault;

defined('YII_DEBUG') or define('YII_DEBUG', (new EnvWithDefault('YII_DEBUG', true))->bool());
defined('YII_ENV') or define('YII_ENV', (new EnvWithDefault('YII_ENV', 'dev'))->string());

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

require __DIR__ . '/../src/Infrastructure/Config/bootstrap.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
