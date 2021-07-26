<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\Dispatch;

try {
    $http = new Dispatch();
    $http->startHttpServer();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}