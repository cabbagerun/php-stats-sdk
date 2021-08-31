<?php

declare(strict_types=1);

namespace Jianzhi\Stats\Service;

use Jianzhi\Stats\Base;
use ClickHouseDB\Client;
use Jianzhi\Stats\ClickHouse\Instance;
use function md5;
use function serialize;

class ClickHouse extends Base
{
    /**
     * 实例
     * @var \ClickHouseDB\Client
     */
    private static $connection;

    /**
     * 创建实例
     * @param array $config
     * @return \ClickHouseDB\Client
     */
    public function connect(array $config = []): Client
    {
        $name = md5(serialize($config));
        if (isset(self::$connection[$name])) {
            return self::$connection[$name];
        }

        $chDbCnf = $this->chDbConfig();
        $connectParams = [
            'host'     => $config['host'] ?? ($chDbCnf['host'] ?? '127.0.0.1'),
            'port'     => $config['port'] ?? ($chDbCnf['port'] ?? 8123),
            'username' => $config['username'] ?? ($chDbCnf['username'] ?? 'default'),
            'password' => $config['password'] ?? ($chDbCnf['password'] ?? ''),
        ];
        self::$connection[$name] = new Client($connectParams);
        self::$connection[$name]->database(($config['db'] ?? 'default'));
        self::$connection[$name]->setConnectTimeOut(3);

        return self::$connection[$name];
    }

    /**
     * ClickHouse Builder
     * @return \Jianzhi\Stats\ClickHouse\Builder
     * @throws \Exception
     */
    public function builder()
    {
        try {
            return (new Instance($this->connect()))->createQueryBuilder();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}