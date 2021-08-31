<?php
/**
 * Calculates the sum. Only works for numbers.
 */

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class Sum
 * @package Jianzhi\Stats\ClickHouse\Functions\Aggregate
 */
class Sum extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "sum({$this->column})";
    }
}
