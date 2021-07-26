<?php

namespace Jianzhi\Stats\base;

use ClickHouseDB\Client;

class CHBase extends Base
{
    private static $db;

    /**
     * 创建实例
     * @param array $config
     * @return Client
     */
    public static function make($config = [])
    {
        if (self::$db) {
            return self::$db;
        }
        $config   = [
            'host'     => $config['host'] ?? (self::$chDbCnf['host'] ?? CLICKHOUSE_HOST),
            'port'     => $config['port'] ?? (self::$chDbCnf['port'] ?? CLICKHOUSE_PORT),
            'username' => $config['username'] ?? (self::$chDbCnf['username'] ?? CLICKHOUSE_USERNAME),
            'password' => $config['password'] ?? (self::$chDbCnf['password'] ?? CLICKHOUSE_PASSWORD),
        ];
        $dbName   = $config['db'] ?? CLICKHOUSE_DB;
        self::$db = new Client($config);
        self::$db->database($dbName);
        self::$db->setConnectTimeOut(3);
        return self::$db;
    }
}