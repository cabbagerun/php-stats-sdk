<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\service\ch\Test;

class Stats extends ControllerBase
{
    public function select()
    {
        $test = new Test();
        return $this->returnData(1000, 'ok', $test->selectTest());
    }
}