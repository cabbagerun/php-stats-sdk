<?php

namespace Jianzhi\Stats\model;

class MDataStats extends Model
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
            ->where('user_id > :user_id')
            ->setParameters([':user_id' => $userId])
            ->setFirstResult(0)
            ->setMaxResults(2)
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
        $condition = ['where' => 'user_id > :user_id', 'bind' => [':user_id' => $userId]];
        $result    = $this->chunk(100, function ($data) {
            foreach ($data as &$value) {
                $value['union_id'] .= '1';
            }
            return $data;
        }, 'user_id,union_id', $condition, ['user_id' => 'desc', 'union_id' => 'desc'], 201);
        return $result;
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function putDataToDb(array $data)
    {
        return $this->insertAll($data);
    }
}