<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions;

use Jianzhi\Stats\ClickHouse\FunctionInterface;

/**
 * Class Length
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class ToInt64 implements FunctionInterface
{
    private $column;

    /**
     * ToInt64 constructor.
     * @param string $column
     */
    public function __construct(string $column)
    {
        $this->column = $column;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "toInt64({$this->column})";
    }
}
