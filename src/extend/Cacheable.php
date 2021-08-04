<?php

namespace Jianzhi\Stats\extend;

use Jianzhi\Stats\extend\cache\SimpleCache;
use Jianzhi\Stats\extend\cache\Operator;
use Jianzhi\Stats\extend\cache\CrossPolicy;

/**
 * Class Cacheable
 * 基于redis的简单缓存
 */
class Cacheable
{
    /**
     * @var SimpleCache
     */
    protected static $cache;

    public function __construct(int $ttl = 60, int $cross = 20)
    {
        if (!static::$cache) {
            static::$cache = new SimpleCache(new Operator(), $ttl, new CrossPolicy($cross));
        }
    }

    public function getCache(...$params)
    {
        return static::$cache->getCache(...$params);
    }
}