<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\MyRedis;

class Data extends ControllerBase
{
    /**
     * 提交数据
     * @return false|string
     */
    public function putData()
    {
        $params = self::$request->param('', []);
        $params = json_decode('{
            "union_id" : "aaaaaaaaaa",
            "visit_ip" : "127.0.0.1",
            "reg_time" : 1627525193,
            "pay_user" : 0,
            "os" : "ios",
            "os_ver" : "13.6",
            "sdk_ver" : "2.0.0",
            "app_id" : "123456",
            "app_ver" : "1.0.0",
            "brand" : "iphone",
            "model" : "AAAAAA",
            "wetch_ver" : "5.0.0",
            "session_id" : "111111111",
            "session_time" : "1627525193",
            "net_type" : "5G",
            "ios_idfa" : "aaaaaaaaaaaaaaa",
            "device_id" : "bbbbbbbbbbbbbbbbbbb",
            "ua" : "user-agent"
        }', true);
        $params['user_id'] = time();
        //验证
        $validate = new \Jianzhi\Stats\validate\DataStats();
        $validate->scene('putData');
        if (!$validate->check($params)) {
            return api_return(1001, (string)$validate->getError());
        }
        //缓冲
        $redisKey = MyRedis::getCacheKey(RK_WAIT_STATS_DATA_BUFF);
        $oldLen = MyRedis::instance()->lLen($redisKey);//todo 队列过长需添加事件监控
        $res = MyRedis::instance()->rPush($redisKey, json_encode($params, JSON_UNESCAPED_UNICODE));
        if ($res <= $oldLen) {
            return api_return(1001, '记录失败');
        }
        return api_return(1000, 'ok');
    }
}