<?php

namespace Jianzhi\Stats;

use Jianzhi\Stats\service\swoole\HttpServer as HServer;

/**
 * swoole http服务
 * Class HttpServer
 * @package Jianzhi\Stats
 */
class HttpServer
{
    private $host;
    private $port;
    private $option;
    private $mode;
    private $sockType;

    public function __construct(
        $host = '0.0.0.0',
        $port = 9501,
        $option = [],
        $mode = SWOOLE_PROCESS,
        $sockType = SWOOLE_SOCK_TCP
    ) {
        $this->host     = $host;
        $this->port     = $port;
        $this->option   = $option;
        $this->mode     = $mode;
        $this->sockType = $sockType;
    }

    public function run()
    {
        try {
            $http = new HServer($this->host, $this->port, $this->option, $this->mode, $this->sockType);
            $http->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}