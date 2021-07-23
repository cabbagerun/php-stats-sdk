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
            'host'     => $config['host'] ?? (self::$chDbCnf['host'] ?? '127.0.0.1'),
            'port'     => $config['port'] ?? (self::$chDbCnf['port'] ?? 8123),
            'username' => $config['username'] ?? (self::$chDbCnf['username'] ?? 'default'),
            'password' => $config['password'] ?? (self::$chDbCnf['password'] ?? ''),
        ];
        $dbName   = $config['db'] ?? (self::$chDbCnf['db'] ?? 'default');
        self::$db = new Client($config);
        self::$db->database($dbName);
        self::$db->setConnectTimeOut(3);
        return self::$db;
    }
}