<?php

namespace Jianzhi\Stats\base;

// use ClickHouseDB\Client;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;

class CHBase extends Base
{
    private $connection;

    /**
     * 创建实例
     * @param array $config
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\Exception
     */
    public function connect(array $config = [])
    {
        if ($this->connection) {
            return $this->connection;
        }

        /* "friendsofdoctrine/dbal-clickhouse"连接方式 */
        $connectParams = [
            'host'          => self::$chDbCnf['host'] ?? ($config['host'] ?? CLICKHOUSE_HOST),
            'port'          => self::$chDbCnf['port'] ?? ($config['port'] ?? CLICKHOUSE_PORT),
            'user'          => self::$chDbCnf['username'] ?? ($config['username'] ?? CLICKHOUSE_USERNAME),
            'password'      => self::$chDbCnf['password'] ?? ($config['password'] ?? CLICKHOUSE_PASSWORD),
            'dbname'        => $config['db'] ?? CLICKHOUSE_DB,
            'driverClass'   => 'FOD\DBALClickHouse\Driver',
            'wrapperClass'  => 'FOD\DBALClickHouse\Connection',
            'driverOptions' => [
                'extremes'                => false,
                'readonly'                => true,
                'max_execution_time'      => 30,
                'enable_http_compression' => 0,
                'https'                   => false
            ],
        ];
        $this->connection = DriverManager::getConnection($connectParams, (new Configuration()));

        /* "smi2/phpclickhouse"连接方式 */
        // $connectParams = [
        //     'host'     => self::$chDbCnf['host'] ?? ($config['host'] ?? CLICKHOUSE_HOST),
        //     'port'     => self::$chDbCnf['port'] ?? ($config['port'] ?? CLICKHOUSE_PORT),
        //     'username' => self::$chDbCnf['username'] ?? ($config['username'] ?? CLICKHOUSE_USERNAME),
        //     'password' => self::$chDbCnf['password'] ?? ($config['password'] ?? CLICKHOUSE_PASSWORD),
        // ];
        // $dbName        = $config['db'] ?? CLICKHOUSE_DB;
        // self::$db      = new Client($connectParams);
        // self::$db->database($dbName);
        // self::$db->setConnectTimeOut(3);

        return $this->connection;
    }

    /**
     * 构建与君
     * @param array $config
     * @return \Doctrine\DBAL\Query\QueryBuilder
     * @throws \Doctrine\DBAL\Exception
     */
    public function builder(array $config = [])
    {
        return $this->connect($config)->createQueryBuilder();
    }
}