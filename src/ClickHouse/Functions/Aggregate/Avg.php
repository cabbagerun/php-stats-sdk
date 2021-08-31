<?php
/**
 * Calculates the average. Only works for numbers. The result is always Float64.
 */

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class Avg
 * @package Jianzhi\Stats\ClickHouse\Functions\Aggregate
 */
class Avg extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "avg({$this->column})";
    }
}
