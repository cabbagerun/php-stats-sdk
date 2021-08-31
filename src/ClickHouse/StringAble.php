<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse;

/**
 * Interface StringAble
 * @package Jianzhi\Stats\ClickHouse
 */
interface StringAble
{
    /**
     * @return string
     */
    public function __toString(): string;
}