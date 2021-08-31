<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class Max
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Max extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "max({$this->column})";
    }
}
