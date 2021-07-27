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
    public static function connect(array $config = [])
    {
        if (self::$db) {
            return self::$db;
        }
        $config   = [
            'host'     => self::$chDbCnf['host'] ?? ($config['host'] ?? CLICKHOUSE_HOST),
            'port'     => self::$chDbCnf['port'] ?? ($config['port'] ?? CLICKHOUSE_PORT),
            'username' => self::$chDbCnf['username'] ?? ($config['username'] ?? CLICKHOUSE_USERNAME),
            'password' => self::$chDbCnf['password'] ?? ($config['password'] ?? CLICKHOUSE_PASSWORD),
        ];
        $dbName   = $config['db'] ?? CLICKHOUSE_DB;
        self::$db = new Client($config);
        self::$db->database($dbName);
        self::$db->setConnectTimeOut(3);
        return self::$db;
    }
}