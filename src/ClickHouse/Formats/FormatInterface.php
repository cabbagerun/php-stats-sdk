<?php
/**
 * Formats
 * The format determines how data is given (written by server as output) to you after SELECTs,
 * and how it is accepted (read by server as input) for INSERTs.
 */

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Formats;

/**
 * Interface FormatInterface
 * @package Jianzhi\Stats\ClickHouse\Formats
 */
interface FormatInterface
{
    static function getName(): string;
}
