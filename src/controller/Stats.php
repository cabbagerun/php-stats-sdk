<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\base\Response;
use Jianzhi\Stats\service\ch\Test;

class Stats extends ControllerBase
{
    public function select($params = [])
    {
        $test = new Test();
        return Response::returnData(1000, 'ok', [$params, $test->selectTest()]);
    }
}