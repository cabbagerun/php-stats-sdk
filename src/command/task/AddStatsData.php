<?php

namespace Jianzhi\Stats\command\task;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\extend\Log;
use Jianzhi\Stats\extend\MyRedis;
use Jianzhi\Stats\service\SDataStats;

class AddStatsData extends Base
{
    private $runMs      = 3000;
    private $runFailMs  = 10000;
    private $runLockEX  = 6;//6秒lock
    private $consumeCnt = 10000;

    public function run()
    {
        $uniqId = uniqid(true);
        swoole_timer_tick(
            $this->runMs,
            function ($timer_id) use ($uniqId) {
                echo '[consumerStatsData-buff] msg:start;time:' . date('Y-m-d H:i:s') . PHP_EOL;
                $this->consumerDataBuff($uniqId);
            }
        );

        swoole_timer_tick(
            $this->runFailMs,
            function ($timer_id) {
                echo '[consumerStatsData-fail] msg:start;time:' . date('Y-m-d H:i:s') . PHP_EOL;
                $this->consumerFailData();
            }
        );
    }

    /**
     * 消费buff数据
     * @param $uniqId
     * @return bool
     */
    private function consumerDataBuff($uniqId)
    {
        // lock
        $lockKey = MyRedis::getCacheKey(DEAL_STATS_DATA_LOCK);
        if (!MyRedis::instance()->set($lockKey, $uniqId, ['NX', 'EX' => $this->runLockEX])) {
            echo '[consumerStatsData-buff] msg:continue;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }

        $redisKey = MyRedis::getCacheKey(WAIT_STATS_DATA_BUFF);
        $data     = MyRedis::instance()->lRange($redisKey, 0, $this->consumeCnt);
        if (empty($data)) {
            echo '[consumerStatsData-buff] msg:nothing to do;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }

        // 批量删除
        MyRedis::instance()->lTrim($redisKey, count($data), -1);

        $this->dealData($data, 'buff');

        // 释放lock
        MyRedis::instance()->del($lockKey);

        return true;
    }

    /**
     * 消费异常数据
     * @return bool
     */
    private function consumerFailData()
    {
        $redisFailKey = MyRedis::getCacheKey(WAIT_STATS_FAIL_DATA);
        $data = MyRedis::instance()->lPop($redisFailKey);
        if (empty($data) || !$data = json_decode($data, true)) {
            echo '[consumerStatsData-fail] msg:nothing to do;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }

        $this->dealData($data, 'fail');

        return true;
    }

    /**
     * 处理数据
     * @param $data
     * @param string $flag
     * @return bool
     */
    private function dealData($data, $flag = 'buff')
    {
        $res = false;
        try {
            $data = array_map(
                function ($value) {
                    if (!is_array($value)) {
                        return json_decode($value, true);
                    }
                },
                $data
            );
            $res  = (new SDataStats())->putDataToDb($data);
        } catch (\Throwable $e) {
            (new Log())->save($e->getMessage(), 'log');
        }

        if (!$res) {
            // 异常数据处理
            $redisFailKey = MyRedis::getCacheKey(WAIT_STATS_FAIL_DATA);
            MyRedis::instance()->rPush($redisFailKey, json_encode($data, JSON_UNESCAPED_UNICODE));

            echo '[consumerStatsData-' . $flag . '] msg:fail;time:' . date('Y-m-d H:i:s') . PHP_EOL;
            return false;
        }
        echo '[consumerStatsData-' . $flag . '] msg:end;time:' . date('Y-m-d H:i:s') . PHP_EOL;
        return true;
    }
}