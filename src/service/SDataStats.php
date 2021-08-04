<?php

namespace Jianzhi\Stats\service;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\extend\MyRedis;
use Jianzhi\Stats\model\MDataStats;

class SDataStats extends Base
{
    public function putDataToCache($data)
    {
        //缓存
        $redisKey = MyRedis::getCacheKey(WAIT_STATS_DATA_BUFF);
        $oldLen = MyRedis::instance()->lLen($redisKey);//todo 队列过长需添加事件监控
        $res = MyRedis::instance()->rPush($redisKey, json_encode($data, JSON_UNESCAPED_UNICODE));
        if ($res <= $oldLen) {
            return json_return(CODE_FAIL, MSG_SUC);
        }
        return json_return(CODE_SUC, MSG_SUC);
    }
    public function selectTest($userId)
    {
        $dataStatsModel = new MDataStats();
        $res1           = $dataStatsModel->selectTest1($userId);
        $res2           = $dataStatsModel->selectTest2($userId);
        return [$res1, $res2];
    }

    public function putDataToDb(array $data)
    {
        if (empty($data)) {
            return false;
        }
        $dataStatsModel = new MDataStats();
        $result = $dataStatsModel->putDataToDb($data);
        return !empty($result);
    }
}