<?php

namespace Jianzhi\Stats\base;

// use ClickHouseDB\Client;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;

class CHBase extends Base
{
    private static $connection;
    protected      $table;

    /**
     * 创建实例
     * @param array $config
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\Exception
     */
    public function connect(array $config = [])
    {
        $name = md5(serialize($config));
        if (isset(self::$connection[$name])) {
            return self::$connection[$name];
        }

        $chDbCnf = $this->chDbConfig();
        /* "friendsofdoctrine/dbal-clickhouse"连接方式 */
        $connectParams           = [
            'host'          => $config['host'] ?? ($chDbCnf['host'] ?? CLICKHOUSE_HOST),
            'port'          => $config['port'] ?? ($chDbCnf['port'] ?? CLICKHOUSE_PORT),
            'user'          => $config['username'] ?? ($chDbCnf['username'] ?? CLICKHOUSE_USERNAME),
            'password'      => $config['password'] ?? ($chDbCnf['password'] ?? CLICKHOUSE_PASSWORD),
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
        self::$connection[$name] = DriverManager::getConnection($connectParams, (new Configuration()));

        /* "smi2/phpclickhouse"连接方式 */
        // $connectParams = [
        //     'host'     => $chDbCnf['host'] ?? ($config['host'] ?? CLICKHOUSE_HOST),
        //     'port'     => $chDbCnf['port'] ?? ($config['port'] ?? CLICKHOUSE_PORT),
        //     'username' => $chDbCnf['username'] ?? ($config['username'] ?? CLICKHOUSE_USERNAME),
        //     'password' => $chDbCnf['password'] ?? ($config['password'] ?? CLICKHOUSE_PASSWORD),
        // ];
        // self::$connection[$name] = new Client($connectParams);
        // self::$connection[$name]->database(($config['db'] ?? CLICKHOUSE_DB));
        // self::$connection[$name]->setConnectTimeOut(3);

        return self::$connection[$name];
    }

    /**
     * 构建sql语句
     * @param array $config
     * @return \Doctrine\DBAL\Query\QueryBuilder
     * @throws \Doctrine\DBAL\Exception
     */
    public function builder(array $config = [])
    {
        return $this->connect($config)->createQueryBuilder()->from($this->table());
    }
}