<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;
use Jianzhi\Stats\ClickHouse\Builder;

/**
 * Class FromQuery
 * @package Jianzhi\Stats\ClickHouse\Components
 */
class FromQuery extends From
{
    /**
     * @var Builder
     */
    private $queryBuilder;

    /**
     * FromQuery constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->queryBuilder = $builder;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->database) {
            $this->queryBuilder->database($this->database);
        }

        return "FROM ({$this->queryBuilder})";
    }
}
