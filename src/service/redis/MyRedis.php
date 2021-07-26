<?php

namespace Jianzhi\Stats\service\redis;

use Jianzhi\Stats\base\RedisBase;

class MyRedis extends RedisBase
{
    /**
     * 实例化
     * @param array $config
     * @param array $attr
     * @return MyRedis|mixed
     */
    public static function make($config = [], $attr = [])
    {
        return parent::getInstance($config, $attr);
    }

    /**
     * 获取缓存key
     * @param $key
     * @param mixed ...$args
     * @return string|null
     */
    public static function getCacheKey($key, ...$args)
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