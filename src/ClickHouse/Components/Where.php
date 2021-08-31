<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;

use Jianzhi\Stats\ClickHouse\ComponentInterface;
use Jianzhi\Stats\ClickHouse\Conditions\ConditionInterface;
use Jianzhi\Stats\ClickHouse\Operators\AbstractOperator;

/**
 * Class Where
 * @package Jianzhi\Stats\ClickHouse\Components
 */
class Where implements ComponentInterface
{
    private $expression = [];

    /**
     * Where constructor.
     * @param ConditionInterface|string $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->expression instanceof AbstractOperator) {
            return "WHERE {$this->expression}";
        }

        if (is_iterable($this->expression)) {
            return "WHERE " . implode(' AND ', $this->expression);
        }

        return "WHERE {$this->expression}";
    }
}
