<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\validate\VDataStats;
use Jianzhi\Stats\service\SDataStats;

class Data extends ControllerBase
{
    /**
     * 提交数据
     * @return false|string
     */
    public function putData()
    {
        $params = $this->request()->param('', []);
        // $params = json_decode('{
        //     "union_id" : "aaaaaaaaaa",
        //     "visit_ip" : "127.0.0.1",
        //     "reg_time" : 1627525193,
        //     "pay_user" : 0,
        //     "os" : "ios",
        //     "os_ver" : "13.6",
        //     "sdk_ver" : "2.0.0",
        //     "app_id" : "123456",
        //     "app_ver" : "1.0.0",
        //     "brand" : "iphone",
        //     "model" : "AAAAAA",
        //     "wetch_ver" : "5.0.0",
        //     "session_id" : "111111111",
        //     "session_time" : "1627525193",
        //     "net_type" : "5G",
        //     "ios_idfa" : "aaaaaaaaaaaaaaa",
        //     "device_id" : "bbbbbbbbbbbbbbbbbbb",
        //     "ua" : "user-agent"
        // }', true);
        // $params['user_id'] = time();

        //验证
        $validate = new VDataStats();
        $validate->scene('putData');
        if (!$validate->check($params)) {
            return json_return(CODE_FAIL, (string)$validate->getError());
        }

        //记录缓存
        $dataStats = new SDataStats();
        return$dataStats->putDataToCache($params);
    }
}