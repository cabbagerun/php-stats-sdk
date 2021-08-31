<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;

use Jianzhi\Stats\ClickHouse\ComponentInterface;

/**
 * Class From
 * @package Jianzhi\Stats\ClickHouse\Components
 */
class From implements ComponentInterface
{
    protected $database;

    public function __construct($table)
    {
    }

    public function __toString(): string
    {
        return 'FROM';
    }

    /**
     * @param string $database
     * @return From
     */
    public function setDatabase(string $database): self
    {
        $this->database = $database;
        return $this;
    }
}
