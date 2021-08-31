<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Operators;

use Jianzhi\Stats\ClickHouse\StringAble;

abstract class AbstractOperator implements StringAble
{
    protected $a;
    protected $b;

    /**
     * Operators constructor.
     * @param $a
     * @param $b
     */
    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}