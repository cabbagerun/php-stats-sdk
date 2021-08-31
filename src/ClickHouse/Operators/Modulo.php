<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class Modulo
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class Modulo extends AbstractOperator
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} % {$this->b}";
    }
}