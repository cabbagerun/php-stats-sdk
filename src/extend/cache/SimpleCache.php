<?php

namespace Jianzhi\Stats\extend\cache;

/**
 * 简单缓存
 * Class SimpleCache
 * @package Timor\Cache
 */
class SimpleCache
{
    /**
     * 缓存类
     * @var Operator
     */
    protected $operator;

    /**
     * 超时时间
     * @var int
     */
    protected $ttl = 0;

    /**
     * 缓存穿透策略
     * @var CrossPolicy
     */
    protected $policy;

    public function __construct($operator, int $ttl = 0, CrossPolicy $policy = null)
    {
        if ($policy) {
            $policy->setOperator($operator);
        }
        $this->operator = $operator;
        $this->ttl      = $ttl;
        $this->policy   = $policy;
    }

    public function getCache(string $key, callable $func, ...$params)
    {
        if ($this->policy !== null) {
            $this->policy->before($key);
        }
        $cache = $this->operator->get(
            $key,
            function () use ($key, $func, $params) {
                $res = $this->toResponse(call_user_func($func, ...$params));
                $this->operator->set($key, $res, $this->ttl);
                return $res;
            }
        );
        if (($cache === false || is_null($cache) || empty($cache)) && $this->policy !== null) {
            return $this->policy->ifNull($key);
        }
        return unserialize($cache);
    }

    private function toResponse($cache)
    {
        if (is_null($cache) || is_string($cache) || is_numeric($cache)) {
            return $cache;
        }
        if (is_array($cache) || $cache instanceof \ArrayAccess || $cache instanceof \stdClass) {
            return serialize($cache);
        }
        throw new \Exception('不支持的数据类型');
    }
}