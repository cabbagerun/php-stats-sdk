<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class NotLike
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class NotLike extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} NOT LIKE '{$this->b}'";
    }
}
