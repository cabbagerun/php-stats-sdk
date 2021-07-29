<?php

namespace Jianzhi\Stats\service\swoole\task;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\service\MyRedis;

class Task extends Base
{
    public function tick()
    {
        swoole_timer_tick(1000, function ($timer_id) {
            echo 'timer#' . $timer_id . ',now:' . date('Y-m-d H:i:s') . PHP_EOL;
            $this->dealData();
        });
    }

    private function dealData()
    {
        $redisKey = MyRedis::getCacheKey(RK_WAIT_STATS_DATA_BUFF);
        $data = MyRedis::instance()->lRange($redisKey, 0, 100);

        // todo 处理insert语句并入库clickHouse
        return true;
    }
}