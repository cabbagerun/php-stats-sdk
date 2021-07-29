<?php

namespace Jianzhi\Stats\service\logic;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\model\DataStatsModel;

class DataStats extends Base
{
    public function selectTest($userId)
    {
        $dataStatsModel = new DataStatsModel();
        $res1           = $dataStatsModel->selectTest1($userId);
        $res2           = $dataStatsModel->selectTest2($userId);
        return [$res1, $res2];
    }

    public function putData(array $data)
    {
        if (empty($data)) {
            return false;
        }
        $dataStatsModel = new DataStatsModel();
        $result = $dataStatsModel->putData($data);
        return !empty($result);
    }
}