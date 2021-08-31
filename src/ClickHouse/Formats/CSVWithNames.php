<?php
/**
 * Also contains header, similar to TabSeparatedWithNames.
 * @link http://clickhouse-docs.readthedocs.io/en/latest/formats/csvwithnames.html
 */

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Formats;

/**
 * Class CSVWithNames
 * @package Jianzhi\Stats\ClickHouse\Formats
 */
class CSVWithNames implements FormatInterface
{
    /**
     * SQL format declaration
     */
    const NAME = 'CSVWithNames';

    /**
     * @return string
     */
    static public function getName(): string
    {
        return self::getName();
    }
}
