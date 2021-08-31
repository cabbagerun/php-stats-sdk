<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Functions;

use Jianzhi\Stats\ClickHouse\FunctionInterface;

/**
 * Class AsExpr
 * @package Jianzhi\Stats\ClickHouse\Functions
 */
class AsExpr implements FunctionInterface
{
    private $input;
    private $name;

    /**
     * AsExpr constructor.
     * @param string $input
     * @param string $name
     */
    public function __construct(string $input, string $name)
    {
        $this->input = $input;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->input . ' AS ' . "`{$this->name}`";
    }
}
