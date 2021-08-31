<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Conditions;

/**
 * Interface ConditionInterface
 * @package Jianzhi\Stats\ClickHouse\Conditions
 */
interface ConditionInterface
{
    public function __toString(): string;
}