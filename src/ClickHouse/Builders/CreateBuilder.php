<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Builders;

use Jianzhi\Stats\ClickHouse\Builders\Create\DatabaseBuilder;
use Jianzhi\Stats\ClickHouse\Builders\Create\TableBuilder;
use Jianzhi\Stats\ClickHouse\Builders\Create\ViewBuilder;
use Jianzhi\Stats\ClickHouse\StringAble;

/**
 * Class CreateBuilder
 * @package Jianzhi\Stats\ClickHouse\Builders
 */
class CreateBuilder implements StringAble
{

    public function database(string $database)
    {
        return new DatabaseBuilder($database);
    }

    public function table(string $table)
    {
        return new TableBuilder($table);
    }

    public function view(string $view)
    {
        return new ViewBuilder($view);
    }

    public function __toString(): string
    {

    }
}