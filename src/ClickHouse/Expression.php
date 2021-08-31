<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse;

use Jianzhi\Stats\ClickHouse\Functions\Aggregate\AndX;
use Jianzhi\Stats\ClickHouse\Functions\Aggregate\Any;
use Jianzhi\Stats\ClickHouse\Functions\AsExpr;
use Jianzhi\Stats\ClickHouse\Functions\Aggregate\Count;
use Jianzhi\Stats\ClickHouse\Functions\Length;
use Jianzhi\Stats\ClickHouse\Functions\OrX;
use Jianzhi\Stats\ClickHouse\Functions\Aggregate\Sum;
use Jianzhi\Stats\ClickHouse\Functions\Aggregate\SumIf;
use Jianzhi\Stats\ClickHouse\Functions\ToInt64;
use Jianzhi\Stats\ClickHouse\Functions\Value;
use Jianzhi\Stats\ClickHouse\Operators\Equals;
use Jianzhi\Stats\ClickHouse\Operators\Less;
use Jianzhi\Stats\ClickHouse\Operators\Greater;
use Jianzhi\Stats\ClickHouse\Operators\LessOrEquals;
use Jianzhi\Stats\ClickHouse\Operators\GreaterOrEquals;
use Jianzhi\Stats\ClickHouse\Operators\Like;
use Jianzhi\Stats\ClickHouse\Operators\NotLike;
use Jianzhi\Stats\ClickHouse\Operators\Minus;
use Jianzhi\Stats\ClickHouse\Operators\Modulo;
use Jianzhi\Stats\ClickHouse\Operators\Multiply;
use Jianzhi\Stats\ClickHouse\Operators\Divide;
use Jianzhi\Stats\ClickHouse\Operators\In;

/**
 * Class Expression
 * @package Jianzhi\Stats\ClickHouse
 */
class Expression
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @return Expression
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Muted
     */
    private function __clone() {}
    private function __construct() {}

    /**
     * @param string $column
     * @return Sum
     */
    public function sum(string $column): Sum
    {
        return new Sum($column);
    }

    /**
     * @param string $column
     * @param string $condition
     * @return SumIf
     */
    public function sumIf(string $column, string $condition): SumIf
    {
        return new SumIf($column, $condition);
    }

    /**
     * @param string $column
     * @return Length
     */
    public function length(string $column): Length
    {
        return new Length($column);
    }

    /**
     * @return Count
     */
    public function count(): Count
    {
        return new Count();
    }

    /**
     * @param string $value
     * @return ToInt64
     */
    public function toInt64(string $value): ToInt64
    {
        return new ToInt64($value);
    }

    /**
     * @param string $value
     * @return Value
     */
    public function value(string $value): Value
    {
        return new Value($value);
    }

    /**
     * @param FunctionInterface $expression
     * @param $name
     * @return AsExpr
     */
    public function as(FunctionInterface $expression, string $name)
    {
        return new AsExpr((string)$expression, $name);
    }

    /**
     * @param string $column
     * @return Any
     */
    public function any(string $column): Any
    {
        return new Any($column);
    }

    /**
     * @param $a
     * @param $b
     * @return Equals
     */
    public function eq($a, $b): Equals
    {
        return new Equals($a, $b);
    }

    /**
     * @param mixed ...$conditions
     * @return AndX
     */
    public function andX(... $conditions): AndX
    {
        return new AndX($conditions);
    }

    /**
     * @param mixed ...$conditions
     * @return OrX
     */
    public function orX(... $conditions): OrX
    {
        return new OrX($conditions);
    }

    /**
     * @param $a
     * @param $b
     * @return Less
     */
    public function lt($a, $b): Less
    {
        return new Less($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return Greater
     */
    public function gt($a, $b): Greater
    {
        return new Greater($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return LessOrEquals
     */
    public function lte($a, $b): LessOrEquals
    {
        return new LessOrEquals($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return GreaterOrEquals
     */
    public function gte($a, $b): GreaterOrEquals
    {
        return new GreaterOrEquals($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return Like
     */
    public function like($a, $b): Like
    {
        return new Like($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return NotLike
     */
    public function nLike($a, $b): NotLike
    {
        return new NotLike($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return Minus
     */
    public function minus($a, $b): Minus
    {
        return new Minus($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return Modulo
     */
    public function modulo($a, $b): Modulo
    {
        return new Modulo($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return Multiply
     */
    public function multiple($a, $b): Multiply
    {
        return new Multiply($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return Divide
     */
    public function divide($a, $b): Divide
    {
        return new Divide($a, $b);
    }

    /**
     * @param $a
     * @param $b
     * @return In
     */
    public function in($a, $b): In
    {
        return new In($a, $b);
    }
}