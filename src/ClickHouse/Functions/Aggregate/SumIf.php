<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class SumIf
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class SumIf extends AggregateFunction
{
    /**
     * @var string
     */
    private $condition;

    /**
     * SumIf constructor.
     * @param string $column
     * @param $condition
     */
    public function __construct(string $column, $condition)
    {
        parent::__construct($column);
        $this->condition = $condition;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "sumIf({$this->column}, $this->condition)";
    }
}