<?php
//swoole内部服务默认配置
!defined('INNER_SWOOLE_HOST') && define('INNER_SWOOLE_HOST', '0.0.0.0');
!defined('INNER_SWOOLE_PORT') && define('INNER_SWOOLE_PORT', 9501);

// clickHouse默认配置
!defined('CLICKHOUSE_HOST') && define('CLICKHOUSE_HOST', '127.0.0.1');
!defined('CLICKHOUSE_PORT') && define('CLICKHOUSE_PORT', 8123);
!defined('CLICKHOUSE_USERNAME') && define('CLICKHOUSE_USERNAME', 'default');
!defined('CLICKHOUSE_PASSWORD') && define('CLICKHOUSE_PASSWORD', '');
!defined('CLICKHOUSE_DB') && define('CLICKHOUSE_DB', 'default');//数据库固定，不可外部覆盖

// redis固定配置
!defined('REDIS_HOST') && define('REDIS_HOST', '127.0.0.1');
!defined('REDIS_PORT') && define('REDIS_PORT', 6379);
!defined('REDIS_PASSWORD') && define('REDIS_PASSWORD', '');
!defined('REDIS_SELECT') && define('REDIS_SELECT', 5);//数据库固定，不可外部覆盖
!defined('REDIS_PREFIX') && define('REDIS_PREFIX', 'stats_');//前缀固定，不可外部覆盖