<?php

declare(strict_types=1);

namespace Jianzhi\Stats;

use function is_array;

class Base
{
    private static $config        = [];
    private static $chDbCnf       = [];//clickHouse配置
    private static $redisCnf      = [];//redis配置

    public function __construct(array $config = [])
    {
        self::$config = $config;
        if (isset(self::$config['ch_db']) && is_array(self::$config['ch_db'])) {
            self::$chDbCnf = self::$config['ch_db'];
        }
        if (isset(self::$config['redis']) && is_array(self::$config['redis'])) {
            self::$redisCnf = self::$config['redis'];
        }
    }

    public function config(): array
    {
        return self::$config;
    }

    public function chDbConfig(): array
    {
        return self::$chDbCnf;
    }

    public function redisConfig(): array
    {
        return self::$redisCnf;
    }
}