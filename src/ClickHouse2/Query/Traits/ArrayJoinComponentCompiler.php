<?php

namespace Jianzhi\Stats\ClickHouse2\Query\Traits;

use Jianzhi\Stats\ClickHouse2\Query\ArrayJoinClause;
use Jianzhi\Stats\ClickHouse2\Query\BaseBuilder as Builder;

trait ArrayJoinComponentCompiler
{
    /**
     * Compiles join to string to pass this string in query.
     *
     * @param Builder         $query
     * @param ArrayJoinClause $join
     *
     * @return string
     */
    protected function compileArrayJoinComponent(Builder $query, ArrayJoinClause $join): string
    {
        $result = [];
        $result[] = 'ARRAY JOIN';
        $result[] = $this->wrap($join->getArrayIdentifier());

        return implode(' ', $result);
    }
}
