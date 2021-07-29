<?php

namespace Jianzhi\Stats\model;

class DataStatsModel extends Model
{
    protected $table = 'jz_data_stats';

    /**
     * @return array
     */
    public function selectTest1()
    {
        $result = $this->builder()
            ->select('*')
            ->from($this->table)
            ->where(' user_id = :user_id')
            ->setParameters(['user_id' => '1'])
            ->execute()
            ->fetchAll();
        return $result;
    }

    /**
     * @return array
     */
    public function selectTest2()
    {
        $result = $this->connect()->query('SELECT 1')->fetchAll();
        return $result;
    }
}