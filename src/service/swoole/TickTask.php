<?php

namespace Jianzhi\Stats\service\swoole;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\service\swoole\task\Task;

class TickTask extends Base
{
    public function run()
    {
        (new Task())->tick();
    }
}