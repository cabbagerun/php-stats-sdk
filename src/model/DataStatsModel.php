<?php

namespace Jianzhi\Stats\model;

class DataStatsModel extends Model
{
    /**
     * @return array
     */
    public function selectTest1()
    {
        $result = self::connect()->select('SELECT * FROM jz_test WHERE user_id = :user_id', ['user_id' => '1'])->rows();
        return $result;
    }

    /**
     * @return array
     */
    public function selectTest2()
    {
        $result = self::connect()->select('SELECT 1')->rows();
        return $result;
    }
}