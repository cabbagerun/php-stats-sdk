<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\redis\MyRedis;

class Data extends ControllerBase
{
    public function receive()
    {
        //todo redis缓冲
        MyRedis::getInstance()->set('aaaaa', 1111);
        $res2 = MyRedis::getInstance()->get('aaaaa');
        MyRedis::getInstance()->del('aaaaa');
        return $this->returnData(1000, 'ok', [$res2, MyRedis::getCacheKey('aaaaaaaaa', 1,2,3)]);
    }
}