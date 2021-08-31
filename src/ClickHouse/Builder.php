<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse;
use Jianzhi\Stats\ClickHouse\Builders\CreateBuilder;

/**
 * Class Builder
 * @package Jianzhi\Stats\ClickHouse
 */
class Builder
{
    /**
     * @param $data
     * @return InsertBuilder
     */
    public function insert($data): InsertBuilder
    {
        return new InsertBuilder($data);
    }

    /**
     * @param mixed ...$params
     * @return SelectBuilder
     * @throws \Exception
     */
    public function select(... $params): SelectBuilder
    {
        return new SelectBuilder($params);
    }

    /**
     * @return CreateBuilder
     */
    public function create(): CreateBuilder
    {
        return new CreateBuilder();
    }

    /**
     * @return Builder
     */
    public function createBuilder(): self
    {
        return new self;
    }

    /**
     * @return Expression
     */
    public function expr()
    {
        return Expression::getInstance();
    }
}
