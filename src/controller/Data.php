<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\base\Response;
use Jianzhi\Stats\service\redis\MyRedis;

class Data extends ControllerBase
{
    public function receive()
    {
        //todo redis缓冲
        MyRedis::make()->set('aaaaa', 1111);
        $res2 = MyRedis::make()->get('aaaaa');
        MyRedis::make()->del('aaaaa');
        return Response::returnData(1000, 'ok', [$res2, MyRedis::getCacheKey('aaaaaaaaa', 1,2,3)]);
    }
}