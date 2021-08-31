<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;

use Jianzhi\Stats\ClickHouse\ComponentInterface;
use Exception;

/**
 * Class Field
 * @package Jianzhi\Stats\ClickHouse\Components
 */
class Field implements ComponentInterface
{
    private $name;
    private $expression;

    /**
     * Field constructor.
     * @param string $name
     * @param null|string $expression
     * @throws Exception
     */
    public function __construct(string $name, ?string $expression = null)
    {
        $this->name = $name;
        $this->expression = $expression;
        $this->validate();
    }

    /**
     * @throws Exception
     */
    private function validate(): void
    {
        if (!$this->name) {
            throw new Exception('Wrong name');
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->expression) {
            return $this->expression . ' AS ' . $this->name;
        }

        return $this->name;
    }
}