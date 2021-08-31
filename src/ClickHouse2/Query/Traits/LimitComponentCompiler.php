<?php

namespace Jianzhi\Stats\ClickHouse2\Query\Traits;

use Jianzhi\Stats\ClickHouse2\Query\BaseBuilder as Builder;
use Jianzhi\Stats\ClickHouse2\Query\Limit;

trait LimitComponentCompiler
{
    /**
     * Compiles limit to string to pass this string in query.
     *
     * @param Builder $builder
     * @param Limit   $limit
     *
     * @return string
     */
    public function compileLimitComponent(Builder $builder, Limit $limit): string
    {
        $limitElements = [];

        if (!is_null($limit->getOffset())) {
            $limitElements[] = $limit->getOffset();
        }

        if (!is_null($limit->getLimit())) {
            $limitElements[] = $limit->getLimit();
        }

        return 'LIMIT '.implode(', ', $limitElements);
    }
}
