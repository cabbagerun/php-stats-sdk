<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse;

/**
 * Interface ComponentInterface
 * @package Jianzhi\Stats\ClickHouse
 */
interface ComponentInterface
{
    /**
     * @return string
     */
    public function __toString(): string;
}