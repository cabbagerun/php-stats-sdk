<?php

namespace Jianzhi\Stats\service\swoole;

use Jianzhi\Stats\base\SwooleBase;

class TickTask
{
    public function run()
    {
        swoole_timer_tick(
            1000,
            function () {
                var_dump(time());
            }
        );
    }
}