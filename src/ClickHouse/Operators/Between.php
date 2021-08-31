<?php

namespace Jianzhi\Stats\ClickHouse\Operators;

/**
 * Class Between
 * @package Jianzhi\Stats\ClickHouse\Operators
 */
class Between extends AbstractOperator
{
    /**
     * @var mixed
     */
    private $c;

    /**
     * NotLike constructor.
     * @param $a
     * @param $b
     * @param $c
     */
    public function __construct($a, $b, $c)
    {
        parent::__construct($a, $b);
        $this->c = $c;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->a} BETWEEN {$this->b} AND {$this->c}";
    }
}
