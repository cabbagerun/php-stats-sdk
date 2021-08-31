<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class Greater
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class Like extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} LIKE '{$this->b}'";
    }
}
