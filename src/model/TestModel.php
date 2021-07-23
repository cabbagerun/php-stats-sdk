<?php

namespace Jianzhi\Stats\model;

class TestModel extends Model
{
    //表名
    protected $table = 'jz_test';

    /**
     * @return array
     */
    public function selectTest1()
    {
        $result = self::make()->select('SELECT 1')->rows();
        return $result;
    }

    /**
     * @return array
     */
    public function selectTest2()
    {
        $result = self::make()->select('SELECT 1')->rows();
        return $result;
    }
}