<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions;

use Jianzhi\Stats\ClickHouse\StringAble;

/**
 * Class OrX
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class OrX implements StringAble
{
    /**
     * @var array
     */
    private $conditions = [];

    /**
     * OrX constructor.
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
        $conditions = implode(" OR ", $this->conditions);

        return "({$conditions})";
    }
}
