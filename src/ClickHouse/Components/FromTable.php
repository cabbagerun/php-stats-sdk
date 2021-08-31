<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;

use Jianzhi\Stats\ClickHouse\ComponentInterface;

class FromTable extends From
{
    private $tableName;

    /**
     * FromTable constructor.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->tableName = $table;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $selector = $this->database ? "{$this->database}.{$this->tableName}": $this->tableName;

        return "FROM {$selector}";
    }
}
