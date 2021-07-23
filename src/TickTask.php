<?php

namespace Jianzhi\Stats;

use Jianzhi\Stats\service\swoole\TickTask as TTask;

/**
 * swoole定时器
 * Class TickTask
 * @package Jianzhi\Stats
 */
class TickTask
{
    public function run()
    {
        try {
            $http = new TTask();
            $http->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}