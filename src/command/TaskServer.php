<?php

namespace Jianzhi\Stats\command;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\command\task\AddStatsData;

class TaskServer extends Base
{
    public function run()
    {
        (new AddStatsData())->run();

        // 解决版本问题
        \Swoole\Event::wait();
    }
}