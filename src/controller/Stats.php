<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;

class Stats extends ControllerBase
{
    public function select()
    {
        $params = $this->request->getParams();
        $validate = new \Jianzhi\Stats\validate\DataStats();
        if (!$validate->check($params)) {
            return api_return(1001, $validate->getError());
        }
        return api_return(1000, 'ok', [$params]);
    }
}