<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class AnyLast
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class AnyLast extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "anyLast({$this->column})";
    }
}
