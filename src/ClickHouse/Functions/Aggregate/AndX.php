<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions\Aggregate;

use Jianzhi\Stats\ClickHouse\StringAble;

/**
 * Class AndX
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class AndX implements StringAble
{
    /**
     * @var array
     */
    private $conditions = [];

    /**
     * AndX constructor.
     * @param array $conditions
     */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $conditions = implode(' AND ', $this->conditions);

        return "({$conditions})";
    }
}
