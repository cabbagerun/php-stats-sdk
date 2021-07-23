<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\HttpServer;

try {
    $http = new HttpServer();
    $http->run();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}