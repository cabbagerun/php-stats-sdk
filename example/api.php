<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\Dispatch;

try {
    $config = ['ch_db' => [], 'redis' => []];
    $http = new Dispatch($config);
    $result = $http->callApi('stats', 'select');
    var_dump($result);
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}