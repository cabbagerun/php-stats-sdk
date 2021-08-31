<?php

namespace Jianzhi\Stats\ClickHouse2\Query\Traits;

use Jianzhi\Stats\ClickHouse2\Query\BaseBuilder as Builder;
use Jianzhi\Stats\ClickHouse2\Query\TwoElementsLogicExpression;

trait WheresComponentCompiler
{
    /**
     * Compiles wheres to string to pass this string in query.
     *
     * @param Builder                      $builder
     * @param TwoElementsLogicExpression[] $wheres
     *
     * @return string
     */
    public function compileWheresComponent(Builder $builder, array $wheres): string
    {
        $result = $this->compileTwoElementLogicExpressions($wheres);

        return "WHERE {$result}";
    }
}
