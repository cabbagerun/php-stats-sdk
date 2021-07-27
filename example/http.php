<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\Dispatch;

try {
    $config = ['swoole_http' => [], 'ch_db' => [], 'redis' => []];
    $http = new Dispatch($config);
    $http->startHttpServer();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}