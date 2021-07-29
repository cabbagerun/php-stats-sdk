<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\logic\DataStats;

class Stats extends ControllerBase
{
    public function select()
    {
        $userId = self::$request->get('user_id', []);
        $validate = new \Jianzhi\Stats\validate\DataStats();
        $validate->scene('select');
        if (!$validate->check(self::$request->param('', []))) {
            return api_return(1001, (string)$validate->getError());
        }
        $data = (new DataStats())->selectTest($userId);
        return api_return(1000, 'ok', $data);
    }
}