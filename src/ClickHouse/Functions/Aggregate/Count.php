<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

/**
 * Class Count
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Count extends AggregateFunction
{
    /**
     * Count constructor.
     */
    public function __construct()
    {
        parent::__construct('');
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "count()";
    }
}
