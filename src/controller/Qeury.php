<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\ch\Test;

class Qeury extends ControllerBase
{
    public function receive()
    {
        $test = new Test();
        return $this->returnData(1000, 'ok', $test->selectTest());
    }
}