<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\logic\DataStats;

class Stats extends ControllerBase
{
    public function select()
    {
        $params = self::$request->param('', []);
        $validate = new \Jianzhi\Stats\validate\DataStats();
        if (!$validate->check($params)) {
            return api_return(1001, $validate->getError());
        }
        $data = (new DataStats())->selectTest();
        return api_return(1000, 'ok', [$params, $data]);
    }
}