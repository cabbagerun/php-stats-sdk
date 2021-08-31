<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

use Jianzhi\Stats\ClickHouse\FunctionInterface;

/**
 * Class AggregateFunction
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
abstract class AggregateFunction implements FunctionInterface
{
    protected $column;

    /**
     * AggregateFunction constructor.
     * @param string $column
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }
}
