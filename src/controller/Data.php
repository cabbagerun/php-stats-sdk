<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\MyRedis;

class Data extends ControllerBase
{
    public function putData()
    {
        $params = self::$request->param('', []);
        //验证
        $validate = new \Jianzhi\Stats\validate\DataStats();
        if (!$validate->check($params)) {
            return api_return(1001, $validate->getError());
        }
        //缓冲
        $redisKey = MyRedis::getCacheKey(RK_WAIT_STATS_DATA_BUFF);
        $res = MyRedis::instance()->lPush($redisKey, json_encode($params, JSON_UNESCAPED_UNICODE));
        if (!$res) {
            return api_return(1001, '记录失败');
        }
        return api_return(1000, 'ok');
    }
}