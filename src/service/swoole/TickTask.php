<?php

namespace Jianzhi\Stats\service\swoole;

use Jianzhi\Stats\base\Base;

class TickTask extends Base
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