<?php

namespace Jianzhi\Stats\extend\cache;

use Jianzhi\Stats\extend\MyRedis;

/**
 * 缓存操作者
 * Class Operator
 * @package Jianzhi\Stats\extend\cache
 */
class Operator
{
    protected $select;

    public function __construct($select = REDIS_SELECT)
    {
        $this->select = $select;
    }

    public function get(string $key, callable $func)
    {
        $res = MyRedis::instance([], ['select' => $this->select])->get($key);
        if ($res === false) {
            return call_user_func($func);
        }
        return $res;
    }

    public function set(string $key, $value, int $ttl = 0): bool
    {
        if ($ttl) {
            return MyRedis::instance([], ['select' => $this->select])->setex($key, $ttl, $value);
        }
        return MyRedis::instance([], ['select' => $this->select])->set($key, $value);
    }
}