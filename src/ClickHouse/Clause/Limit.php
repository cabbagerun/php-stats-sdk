<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Clause;

/**
 * Class Limit
 * @package Jianzhi\Stats\ClickHouse\Clause
 */
class Limit
{
    private $limit;
    private $offset;

    /**
     * Limit constructor.
     * @param int $limit
     * @param int|null $offset
     */
    public function __construct(int $limit, ?int $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->offset) {
            return "LIMIT {$this->offset}, {$this->limit}";
        }

        return "LIMIT {$this->limit}";
    }
}