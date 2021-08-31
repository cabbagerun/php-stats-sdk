<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class Min
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Min extends AggregateFunction
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "min({$this->column})";
    }
}
