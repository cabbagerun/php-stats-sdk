<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class In
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class In extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} IN ({$this->b})";
    }
}
