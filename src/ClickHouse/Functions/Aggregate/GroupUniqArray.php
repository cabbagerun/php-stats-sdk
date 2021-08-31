<?php
/**
 * Creates an array from different argument values.
 * Memory consumption is the same as for the ‘uniqExact’ function.
 */

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class GroupUniqArray
 * @package Jianzhi\Stats\ClickHouse\Functions\Aggregate
 */
class GroupUniqArray extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "groupUniqArray({$this->column})";
    }
}
