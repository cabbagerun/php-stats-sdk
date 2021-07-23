<?php

namespace Jianzhi\Stats\base;

abstract class SwooleBase extends Base
{
    private $serv;
    private $host   = '0.0.0.0';
    private $port   = 9501;
    private $option = [
        'daemonize'    => false,
        'log_file'     => __DIR__ . '/../log/error.log',
        'log_level'    => SWOOLE_LOG_NOTICE | SWOOLE_LOG_WARNING | SWOOLE_LOG_ERROR,
        'log_rotation' => SWOOLE_LOG_ROTATION_DAILY,
    ];

    /**
     * 初始化
     * SwooleBase constructor.
     * @param string $host
     * @param int $port
     * @param array $option
     */
    public function __construct($host, $port, $option)
    {
        if ($host) {
            $this->host = $host;
        }
        if ($port) {
            $this->port = $port;
        }
        if ($option) {
            $this->option = array_merge($this->option, $option);
        }
        $this->serv = $this->instance($this->host, $this->port);
        $this->serv->set($this->option);
        parent::__construct();
    }

    /**
     * 自定义实例化
     * @param string $host
     * @param int $port
     * @return mixed
     */
    abstract public function instance($host, $port);


    /**
     * 回调
     * @param $serv
     * @return mixed
     */
    abstract public function callbacks($serv);

    /**
     * 运行
     * @return mixed
     */
    public function run()
    {
        $this->callbacks($this->serv);
        $this->serv->start();
    }
}