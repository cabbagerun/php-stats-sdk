<?php

declare(strict_types=1);

namespace Jianzhi\Stats;

use Jianzhi\Stats\Service\ClickHouse;
use Jianzhi\Stats\Service\MyRedis;

/**
 * ClickHouse SDK Client
 * @author wenjian
 * @date 2021/8/31
 */
class Client
{
    /**
     * @var array
     */
    private $config = [];

    public function __construct(array $config = [])
    {
        $config && $this->config = $config;
    }

    /**
     * ClickHouse构建
     * @return \Jianzhi\Stats\ClickHouse\Builder
     * @throws \Exception
     */
    public function builder()
    {
        $operator = new ClickHouse($this->config);
        return $operator->Builder();
    }
}