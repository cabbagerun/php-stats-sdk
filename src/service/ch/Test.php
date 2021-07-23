<?php

namespace Jianzhi\Stats\service\ch;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\model\TestModel;

class Test extends Base
{
    public function selectTest()
    {
        $testModel = new TestModel();
        $res1      = $testModel->selectTest1();
        $res2      = $testModel->selectTest1();
        return [$res1, $res2];
    }
}