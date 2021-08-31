<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class Any
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Any extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "any({$this->column})";
    }
}