<?php

namespace Jianzhi\Stats\ClickHouse2\Query\Traits;

use Jianzhi\Stats\ClickHouse2\Query\BaseBuilder as Builder;
use Jianzhi\Stats\ClickHouse2\Query\TwoElementsLogicExpression;

trait HavingsComponentCompiler
{
    /**
     * Compiles havings to string to pass this string in query.
     *
     * @param Builder                      $builder
     * @param TwoElementsLogicExpression[] $havings
     *
     * @return string
     */
    public function compileHavingsComponent(Builder $builder, array $havings): string
    {
        $result = $this->compileTwoElementLogicExpressions($havings);

        return "HAVING {$result}";
    }
}
