<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse;

use Jianzhi\Stats\ClickHouse\Clause\GroupBy;
use Jianzhi\Stats\ClickHouse\Clause\Having;
use Jianzhi\Stats\ClickHouse\Clause\Limit;
use Jianzhi\Stats\ClickHouse\Clause\OrderBy;
use Jianzhi\Stats\ClickHouse\Components\From;
use Jianzhi\Stats\ClickHouse\Components\FromQuery;
use Jianzhi\Stats\ClickHouse\Components\FromTable;
use Jianzhi\Stats\ClickHouse\Components\Select;
use Jianzhi\Stats\ClickHouse\Components\Where;
use Jianzhi\Stats\ClickHouse\Conditions\ConditionInterface;

/**
 * Class SelectBuilder
 * @package Jianzhi\Stats\ClickHouse
 */
class SelectBuilder extends Builder implements StringAble
{
    /**
     * @var bool
     */
    private $union = false;

    /**
     * @var Select[]
     */
    private $select = [];

    /**
     * @var From[]
     */
    private $from = [];

    /**
     * @var Where[]
     */
    private $where = [];

    /**
     * @var array
     */
    private $database = [];

    /**
     * @var string
     */
    private $hash;

    /**
     * @var array
     */
    private $hashes = [];

    /** @var bool[] */
    private $distinct;

    /**
     * @var Parameters[]
     */
    private $parameters = [];

    /**
     * @var Limit[]
     */
    private $limits = [];

    /**
     * @var OrderBy[]
     */
    private $orders = [];

    /**
     * @var GroupBy[]
     */
    private $groups = [];

    /**
     * @var Having[]
     */
    private $havings = [];

    /**
     * SelectBuilder constructor.
     * @param mixed ...$params
     * @throws \Exception
     */
    public function __construct($params)
    {
        $this->hash = uniqid();
        $this->select[$this->hash] = new Select($params);
        $this->hashes[] = $this->hash;
    }

    /**
     * @param mixed ...$params
     * @return $this
     * @throws \Exception
     */
    public function andSelect(... $params)
    {
        $this->select[$this->hash]->addSelect($params);
        return $this;
    }

    /**
     * @return Builder
     */
    public function distinct(): self
    {
        $this->distinct[$this->hash] = true;
        return $this;
    }

    /**
     * @param Builder|string $tableOrBuilder
     * @return $this
     * @throws \Exception
     */
    public function from($tableOrBuilder)
    {
        if ($tableOrBuilder instanceof Builder) {
            $this->from[$this->hash] = new FromQuery($tableOrBuilder);
            return $this;
        }

        if (is_string($tableOrBuilder)) {
            $this->from[$this->hash] = new FromTable($tableOrBuilder);
            return $this;
        }

        throw new \Exception('Wrong table');
    }

    /**
     * @param string $name
     * @param $value
     * @return Builder
     */
    public function setParameter(string $name, $value): self
    {
        if (!isset($this->parameters[$this->hash])) {
            $this->parameters[$this->hash] = new Parameters;
        }

        $this->parameters[$this->hash]->addParameter(
            new Parameter($name, $value)
        );

        return $this;
    }

    /**
     * @param string $dbName
     * @return Builder
     */
    public function database(string $dbName): self
    {
        $this->database[$this->hash] = $dbName;
        return $this;
    }

    /**
     * @param int $limit
     * @param int|null $offset
     * @return Builder
     */
    public function limit(int $limit, ?int $offset = null): self
    {
        $this->limits[$this->hash] = new Limit($limit, $offset);
        return $this;
    }

    /**
     * @param mixed ...$orders
     * @return Builder
     * @return self
     * @throws \Exception
     */
    public function orderBy(... $orders): self
    {
        $this->orders[$this->hash] = new OrderBy($orders);
        return $this;
    }

    /**
     * @param mixed ...$orders
     * @return self
     * @throws \Exception
     */
    public function andOrderBy(... $orders): self
    {
        if (!isset($this->orders[$this->hash])) {
            throw new \Exception('You must call "orderBy" method first');
        }

        $this->orders[$this->hash]->addOrder($orders);
        return $this;
    }

    /**
     * @param mixed ...$groups
     * @return Builder
     * @return self
     * @throws \Exception
     */
    public function groupBy(... $groups): self
    {
        $this->groups[$this->hash] = new GroupBy($groups);
        return $this;
    }

    /**
     * @param mixed ...$groups
     * @return self
     * @throws \Exception
     */
    public function andGroupBy(... $groups): self
    {
        if (!isset($this->groups[$this->hash])) {
            throw new \Exception('You must call "orderBy" method first');
        }

        $this->groups[$this->hash]->addGroup($groups);
        return $this;
    }

    /**
     * @param mixed ...$havings
     * @return Builder
     * @return self
     * @throws \Exception
     */
    public function having(... $havings): self
    {
        $this->havings[$this->hash] = new Having($havings);
        return $this;
    }

    /**
     * @param mixed ...$havings
     * @return self
     * @throws \Exception
     */
    public function andHaving(... $havings): self
    {
        if (!isset($this->havings[$this->hash])) {
            throw new \Exception('You must call "orderBy" method first');
        }

        $this->havings[$this->hash]->addHaving($havings);
        return $this;
    }

    /**
     * @return self
     */
    public function unionAll(): self
    {
        $this->union = true;
        $this->hash = uniqid();
        $this->hashes[] = $this->hash;
        return $this;
    }

    /**
     * @param ConditionInterface|string $expr
     * @return $this
     */
    public function where($expr)
    {
        $this->where[$this->hash] = new Where($expr);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->union) {
            $queries = [];

            foreach ($this->hashes as $hash) {
                $queries[] = $this->createSQLQuery($hash);
            }

            return implode(' UNION ALL ', $queries);
        }

        return $this->createSQLQuery($this->hash);
    }

    /**
     * @param string $hash
     * @return string
     */
    private function createSQLQuery(string $hash): string
    {
        // SELECT ...
        $query = "{$this->select[$hash]}";

        // FROM
        if (isset($this->from[$hash])) {
            $from = $this->from[$hash];

            if (isset($this->database[$hash])) {
                $from->setDatabase($this->database[$hash]);
            }

            $query .= " {$from}";
        }

        // PREWHERE

        // WHERE
        if (isset($this->where[$hash])) {
            $where = " " . $this->where[$hash];

            $query .= " {$where}";
        }

        // GROUP
        if (isset($this->groups[$this->hash])) {
            $query .= " " . $this->groups[$this->hash];
        }

        // HAVING
        if (isset($this->havings[$this->hash])) {
            $query .= " " . $this->havings[$this->hash];
        }

        // ORDER
        if (isset($this->orders[$this->hash])) {
            $query .= " " . $this->orders[$this->hash];
        }

        // LIMIT
        if (isset($this->limits[$hash])) {
            $query .= " " . $this->limits[$hash];
        }

        if (isset($this->parameters[$hash])) {
            foreach ($this->parameters[$hash] as $parameter) {
                $query = $parameter->inject($query);
            }
        }

        return $query;
    }
}
