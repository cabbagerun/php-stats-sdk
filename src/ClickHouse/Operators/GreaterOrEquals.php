<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class GreaterOrEquals
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class GreaterOrEquals extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} >= {$this->b}";
    }
}
