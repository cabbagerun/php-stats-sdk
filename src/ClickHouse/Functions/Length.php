<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions;

use Jianzhi\Stats\ClickHouse\Functions\Aggregate\AggregateFunction;

/**
 * Class Length
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Length extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "length({$this->column})";
    }
}
