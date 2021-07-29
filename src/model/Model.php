<?php

namespace Jianzhi\Stats\model;

use Jianzhi\Stats\base\CHBase;

class Model extends CHBase
{
    //todo 可封装公共的业务操作

    /**
     * 获取数据表
     * @return mixed
     * @throws \Exception
     */
    protected function table()
    {
        if (!$this->table) {
            throw new \Exception('table property cannot be empty.');
        }
        return $this->table;
    }

    /**
     * 批量插入数据
     * @param $tale
     * @param array $data
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function insertAll($tale, array $data)
    {
        try {
            $currentItem = current($data);
            if (empty($currentItem)) {
                return false;
            }
            if (!is_array($currentItem)) {
                $currentItem = $data;
                $data        = [$data];
            }
            $prepare = array_pad([], count($currentItem), '?');
            $values  = [];
            $sql     = 'INSERT INTO ' . $tale . ' ( ' . implode(', ', array_keys($currentItem)) . ' ) VALUES ';
            foreach ($data as $value) {
                $values = array_merge($values, array_values($value));
                $sql   .= ' (' . implode(', ', $prepare) . '),';
            }
            $sql = trim($sql, ',') . ';';
            $statement = $this->connect()->prepare($sql);
            return $statement->execute($values);
        } catch (\Throwable $e) {
            // todo log
            return false;
        }
    }
}