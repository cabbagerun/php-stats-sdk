<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions;

use Jianzhi\Stats\ClickHouse\Conditions\ConditionInterface;

/**
 * Class AnyLast
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Equal implements ConditionInterface
{
    /**
     * @var string
     */
    private $column;


    private $value;

    public function __construct(string $column, $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "`{$this->column}` = {$this->value}";
    }
}
