<?php

define('ROOT_PATH', __DIR__ . '/..');
require ROOT_PATH . '/vendor/autoload.php';

Antimonial\Core\DotEnv::load(ROOT_PATH . '/.env');
Antimonial\Core\Config::load('app');
Antimonial\Core\Config::load('database');
Antimonial\Core\ErrorHandler::enableDebug((bool) env('APP_DEBUG', false));

$app = require ROOT_PATH . '/bootstrap/app.php';
$app->run();
