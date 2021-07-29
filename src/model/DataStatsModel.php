<?php

namespace Jianzhi\Stats\model;

class DataStatsModel extends Model
{
    protected $table = 'jz_data_stats';

    /**
     * @param $userId
     * @return mixed[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function selectTest1($userId)
    {
        $result = $this->builder()
            ->select('*')
            ->where('user_id = :user_id')
            ->setParameters(['user_id' => $userId])
            ->execute()
            ->fetchAll();
        return $result;
    }

    /**
     * @param $userId
     * @return mixed[]
     * @throws \Doctrine\DBAL\Exception
     */
    public function selectTest2($userId)
    {
        $result = $this->connect()->query('SELECT 1')->fetchAll();
        return $result;
    }

    /**
     * @param array $data
     * @param array $types
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function putData(array $data, array $types = [])
    {
        return $this->insertAll($this->table, $data);
    }
}