<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Jianzhi\Stats\TickTask;

try {
    $tick = new TickTask();
    $tick->run();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}