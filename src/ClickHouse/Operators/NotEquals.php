<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class NotEquals
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class NotEquals extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} != {$this->b}";
    }
}
