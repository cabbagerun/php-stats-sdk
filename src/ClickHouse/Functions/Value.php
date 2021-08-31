<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions;

use Jianzhi\Stats\ClickHouse\StringAble;

/**
 * Class Value
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class Value implements StringAble
{
    /**
     * @var string
     */
    private $value;

    /**
     * Value constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
