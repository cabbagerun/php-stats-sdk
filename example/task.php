<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\Dispatch;

try {
    $tick = new Dispatch();
    $tick->startTickTask();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}