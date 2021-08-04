<?php

namespace Jianzhi\Stats\model;

use Jianzhi\Stats\base\CHBase;

class Model extends CHBase
{
    //todo 可封装公共的业务操作
    private $where;//where条件 string 【exp：id > :id】
    private $bind;//where条件的绑定参数 array 【['id' => 1]】
    private $groupBy;//group by分组 string 【exp：id having count(a) > 100】
    private $limit;//分页 int

    /**
     * 获取数据表
     * @param string $table
     * @return mixed
     */
    protected function table($table = '')
    {
        if ($table) {
            $this->table = $table;
            return $this;
        }
        return $this->table;
    }

    /**
     * 分页偏移量
     * @param int $page
     * @param int $limit
     * @return int
     */
    protected function offset(int $page = 1, int $limit = 10)
    {
        ($page < 1) && $page = 1;
        ($limit < 1) && $limit = 10;
        return ($page - 1) * $limit;
    }

    /**
     * where条件
     * @param string $where
     * @return $this
     */
    protected function where(string $where)
    {
        $this->where = $where;
        return $this;
    }

    /**
     * 绑定where条件
     * @param array $condition
     * @return $this
     */
    protected function bindWhere(array $condition)
    {
        $this->where = (string)($condition['where'] ?? '');
        $this->bind  = (array)($condition['bind'] ?? []);
        return $this;
    }

    /**
     * 分组
     * @param string $groupBy
     * @return $this
     */
    protected function groupBy(string $groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * 条目数
     * @param int $limit
     * @return $this
     */
    protected function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * 构建sql语句
     * @param string $sql
     * @return string
     */
    protected function buildSql(string $sql)
    {
        if ($this->where) {
            $sql .= " WHERE {$this->where} ";
        }
        if ($this->groupBy) {
            $sql .= " GROUP BY {$this->groupBy} ";
        }
        if ($this->limit > 0) {
            $sql .= " LIMIT 0, {$this->limit} ";
        }
        return $sql;
    }

    /**
     * 绑定参数
     * @param $stmt
     * @return mixed
     */
    protected function bindParams($stmt)
    {
        if ($this->bind) {
            foreach ($this->bind as $bKey => $bVal) {
                if (is_array($bVal) && count($bVal) == 2) {
                    $stmt->bindValue(ltrim($bKey, ':'), $bVal[0], $bVal[1]);
                } else {
                    $stmt->bindValue(ltrim($bKey, ':'), $bVal);
                }
            }
        }
        return $stmt;
    }

    /**
     * 批量插入数据
     * @param array $data
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    protected function insertAll(array $data)
    {
        $currentItem = current($data);
        if (!is_array($currentItem)) {
            $currentItem = $data;
            $data        = [$data];
        }
        $prepare = array_pad([], count($currentItem), '?');
        $values  = [];
        $sql     = 'INSERT INTO ' . $this->table() . ' ( ' . implode(', ', array_keys($currentItem)) . ' ) VALUES ';
        foreach ($data as $value) {
            $values = array_merge($values, array_values($value));
            $sql    .= ' (' . implode(', ', $prepare) . '),';
        }
        $sql       = trim($sql, ',') . ';';
        $statement = $this->connect()->prepare($sql);
        return $statement->execute($values);
    }

    /**
     * 分批数据返回处理
     * @param int $count
     * @param callable $callback
     * @param string $fields
     * @param array $condition
     * @param array $orders
     * @param int $limit
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    protected function chunk(
        int $count,
        callable $callback,
        string $fields = '*',
        array $condition = [],
        array $orders = [],
        int $limit = 0
    ) {
        if ($limit > 0 && $limit < $count) {
            $count = $limit;
        }
        $result   = [];
        $countAll = 0;
        $page     = 1;
        $query    = function ($page, $count, $fields, $condition, $orders) {
            $fields  = $fields ?: '*';
            $where   = (string)($condition['where'] ?? '');
            $bind    = $condition['bind'] ?? [];
            $builder = $this->builder()->select($fields);
            if ($where) {
                $builder = $builder->where($where)->setParameters($bind);
            }
            $offset  = $this->offset($page, $count);
            $builder = $builder->setFirstResult($offset)->setMaxResults($count);
            if ($orders) {
                foreach ($orders as $part => $sort) {
                    $builder = $builder->addOrderBy($part, $sort);
                }
            }
            return $builder;
        };

        $resultSet = $query($page, $count, $fields, $condition, $orders)->execute()->fetchAll();
        while (($resultCount = count($resultSet)) > 0) {
            $countAll += $resultCount;
            $callDeal = call_user_func($callback, $resultSet);
            if ($callDeal === false) {
                break;
            }
            $result = array_merge($result, $callDeal);
            if ($limit > 0 && $countAll >= $limit) {
                $result = array_slice($result, 0, $limit);
                break;
            }
            $page++;
            $resultSet = $query($page, $count, $fields, $condition, $orders)->execute()->fetchAll();
        }
        unset($conn, $connClone, $connWhile);

        return $result;
    }

    /**
     * 聚合查询
     * @param string $fieldExp
     * @param string|null $column
     * @param string $columnKey
     * @return array
     * @throws \Doctrine\DBAL\Exception
     */
    protected function aggregation(string $fieldExp, string $column = null, string $columnKey = '')
    {
        $sql  = "SELECT {$fieldExp} FROM {$this->table()} ";
        $sql  = $this->buildSql($sql);
        $stmt = $this->connect()->prepare($sql);
        $stmt = $this->bindParams($stmt);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($column || $columnKey) {
            $result = array_column($result, $column, $columnKey);
        }
        return $result;
    }
}