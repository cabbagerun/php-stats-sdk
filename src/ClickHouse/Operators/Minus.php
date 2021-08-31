<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class Plus
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class Minus extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} - {$this->b}";
    }
}