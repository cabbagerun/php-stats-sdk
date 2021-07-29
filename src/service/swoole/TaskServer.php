<?php

namespace Jianzhi\Stats\service\swoole;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\service\swoole\task\AddStatsData;

class TaskServer extends Base
{
    public function run()
    {
        (new AddStatsData())->run();
    }
}