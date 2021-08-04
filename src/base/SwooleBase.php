<?php

namespace Jianzhi\Stats\base;

abstract class SwooleBase extends Base
{
    private $serv;
    private $host   = INNER_SWOOLE_HOST;
    private $port   = INNER_SWOOLE_PORT;
    private $option = INNER_SWOOLE_OPTION;

    /**
     * 初始化设置
     * todo 如有配置需覆盖可重写覆盖$config参数
     * @param array $config
     * @return $this
     */
    public function init(array $config = [])
    {
        $config = array_merge($config, $this->setOption());
        $swooleHttpCnf = $this->swooleHttpConfig();
        $this->host   = $config['host'] ?? ($swooleHttpCnf['host'] ?? $this->host);
        $this->port   = $config['port'] ?? ($swooleHttpCnf['port'] ?? $this->port);
        $this->option = array_merge(
            $this->option,
            array_merge(($swooleHttpCnf['option'] ?? []), ($config['option'] ?? []))
        );
        $logDir       = pathinfo($this->option['log_file'], PATHINFO_DIRNAME);
        if (!is_dir($logDir)) {
            mkdir($logDir);
        }
        $this->serv = $this->instance($this->host, $this->port);
        $this->serv->set($this->option);
        return $this;
    }

    /**
     * 设置选线
     * @return mixed
     */
    abstract public function setOption();

    /**
     * 自定义实例化
     * @param string $host
     * @param int $port
     * @return mixed
     */
    abstract public function instance(string $host, int $port);


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