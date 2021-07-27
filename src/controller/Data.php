<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\redis\MyRedis;

class Data extends ControllerBase
{
    public function putData()
    {
        $params = $this->getParams();
        //todo 处理数据
        //todo redis缓冲
        $redisKey = MyRedis::instance(RK_WAIT_STATS_DATA_BUFF);
        MyRedis::instance()->set('aaaaa', 1111);
        $res2 = MyRedis::instance()->get('aaaaa');
        MyRedis::instance()->del('aaaaa');
        return api_return(1000, 'ok', [$params, $res2, MyRedis::getCacheKey('aaaaaaaaa', 1,2,3)]);
    }
}