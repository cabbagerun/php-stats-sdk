<?php

namespace Jianzhi\Stats\service;

use Jianzhi\Stats\base\RedisBase;

class MyRedis extends RedisBase
{
    /**
     * 获取缓存key
     * @param string $key
     * @param mixed ...$args
     * @return string|null
     */
    public static function getCacheKey(string $key, ...$args)
    {
        if (empty($key)) {
            return null;
        }
        $prefix  = self::$prefix;
        $addArgs = '';
        foreach ($args as $arg) {
            $addArgs .= ':' . $arg;
        }
        return $prefix . $key . $addArgs;
    }
}