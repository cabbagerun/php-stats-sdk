<?php

namespace Jianzhi\Stats\service\logic;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\model\DataStatsModel;

class DataStats extends Base
{
    public function selectTest()
    {
        $dataStatsModel = new DataStatsModel();
        $res1           = $dataStatsModel->selectTest1();
        $res2           = $dataStatsModel->selectTest2();
        return [$res1, $res2];
    }
}