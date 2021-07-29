<?php

namespace Jianzhi\Stats\service\swoole\task;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\service\MyRedis;

class AddStatsData extends Base
{
    private $runMs = 3000;
    private $runLockS = 6;//6秒lock
    private $consumeCnt = 10000;

    public function run()
    {
        swoole_timer_tick(
            $this->runMs,
            function ($timer_id) {
                echo '[consumerStatsData] msg:start;time:' . date('Y-m-d H:i:s') . PHP_EOL;
                $this->dealData($timer_id);
            }
        );
    }

    /**
     * 处理数据
     * @param $timer_id
     * @return bool
     */
    private function dealData($timer_id)
    {
        $lockKey = MyRedis::getCacheKey(RK_DEAL_STATS_DATA_LOCK);
        if (!MyRedis::instance()->set($lockKey, $timer_id, ['NX', 'EX' => $this->runLockS])) {
            echo '[consumerStatsData] msg:continue;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }

        $redisKey = MyRedis::getCacheKey(RK_WAIT_STATS_DATA_BUFF);
        $data     = MyRedis::instance()->lRange($redisKey, 0, $this->consumeCnt);
        if (empty($data)) {
            echo '[consumerStatsData] msg:nothing to do;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }

        $data = array_map(function ($value) {
            return json_decode($value, true);
        }, $data);
        $res = (new \Jianzhi\Stats\service\logic\DataStats())->putData($data);

        //释放lock
        MyRedis::instance()->del($lockKey);

        if (!$res) {
            echo '[consumerStatsData] msg:fail;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }
        //批量删除
        MyRedis::instance()->lTrim($redisKey, count($data), -1);
        echo '[consumerStatsData] msg:end;time:' . date('Y-m-d H:i:s') . PHP_EOL;
        return true;
    }
}