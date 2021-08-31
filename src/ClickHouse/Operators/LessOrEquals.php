<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class LessOrEquals
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class LessOrEquals extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} <= {$this->b}";
    }
}
