<?php

namespace Jianzhi\Stats\extend\cache;

/**
 * 缓存穿透策略
 * Class CrossPolicy
 * @package Jianzhi\Stats\extend\cache
 */
class CrossPolicy
{
    protected $operator;

    protected $ttl;

    protected $keyRegx;//检查key的正则

    protected $nullValue = '';

    public function __construct(int $ttl, string $keyRegx = '', $nullValue = '')
    {
        $this->ttl       = $ttl;
        $this->keyRegx   = $keyRegx;
        $this->nullValue = $nullValue;
    }

    public function ifNull(string $key)
    {
        $this->operator->set($key, $this->nullValue, $this->ttl);
        return $this->nullValue;
    }

    public function before(string $key)
    {
        if ($this->keyRegx && !preg_match($this->keyRegx, $key)) {
            throw new \Exception('key不符合要求');
        }
    }

    public function setOperator(Operator $op)
    {
        $this->operator = $op;
    }
}