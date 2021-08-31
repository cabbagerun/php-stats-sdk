<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Builders\Create;

use Jianzhi\Stats\ClickHouse\StringAble;

/**
 * Class ViewBuilder
 * @package Jianzhi\Stats\ClickHouse\Builders\Create
 */
class ViewBuilder implements StringAble
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var
     */
    private $ifNotExists;

    /**
     * ViewBuilder constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param bool $ifNotExists
     * @return ViewBuilder
     */
    public function setIfNotExists(bool $ifNotExists = true): self
    {
        $this->ifNotExists = $ifNotExists;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $flag = $this->ifNotExists ? " IF NOT EXISTS" : '';

        return "CREATE TABLE{$flag} {$this->name}";
    }
}