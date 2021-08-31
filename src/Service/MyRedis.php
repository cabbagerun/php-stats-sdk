<?php

declare(strict_types=1);

namespace Jianzhi\Stats\Service;

use Jianzhi\Stats\Base;
use function extension_loaded;
use function array_merge;
use function time;
use function md5;
use function implode;

/**
 * Class MyRedis
 * @package Jianzhi\Stats\Common
 */
class MyRedis extends Base
{
    /**
     * 实例化的对象,单例模式.
     * @var array
     */
    static private $_instance = null;

    /**
     * @var
     */
    private $k;

    const HOST = '127.0.0.1';
    const PORT = 6379;
    const PASSWORD = '';
    const SELECT = 5;
    const PREFIX = 'stats_';

    /**
     * 实例
     * @var \Redis
     */
    private $redis;

    /**
     * 当前地址
     * @var string
     */
    private $host = self::HOST;

    /**
     * 当前端口
     * @var int
     */
    private $port = self::PORT;

    /**
     * 密码
     * @var string
     */
    private $auth = self::PASSWORD;

    /**
     * 连接属性数组
     * @var array
     */
    private $attr = [
        'select' => self::SELECT,//选择的数据库
        'timeout' => 30,//连接超时时间，redis配置文件中默认为300秒
        'persistent' => false,//是否长连接
    ];

    /**
     * 什么时候重新建立连接
     * @var
     */
    private $expireTime;

    /**
     * 连接数据库
     * @param array $config [host,port,password]
     * @param array $attr [select,timeout,persistent]
     */
    private function connect(array $config = [], array $attr = [])
    {
        if (extension_loaded('redis')) {
            $this->attr = array_merge($this->attr, $attr);
            $this->expireTime = time() + $this->attr['timeout'];
            $redisCnf = $this->redisConfig();
            $this->host = $config['host'] ?? ($redisCnf['host'] ?? '127.0.0.1');
            $this->port = $config['port'] ?? ($redisCnf['port'] ?? 6379);
            $this->auth = $config['password'] ?? ($redisCnf['password'] ?? '');
            $this->redis = new \Redis();
            if (isset($this->attr['persistent']) && $this->attr['persistent']) {
                $persistentId = 'persistent_id_' . $this->attr['select'];
                $this->redis->pconnect($this->host, $this->port, $this->attr['timeout'], $persistentId);
            } else {
                $this->redis->connect($this->host, $this->port, $this->attr['timeout']);
            }
            if ($this->auth) {
                $this->redis->auth($this->auth);
            }
        } else {
            throw new \BadFunctionCallException('not support: redis');
        }
    }

    /**
     * 获取实例化
     * @param array $config
     * @param array $attr
     * @return \Redis
     */
    public static function instance(array $config = [], array $attr = [])
    {
        $attr['select'] = $attr['select'] ?? self::SELECT;
        $k = md5(implode('', $config) . $attr['select']);
        $connect = function ($config, $attr, $k) {
            self::$_instance[$k] = new self();
            self::$_instance[$k]->connect($config, $attr);
            self::$_instance[$k]->k = $k;
            self::$_instance[$k]->select = $attr['select'];
            //如果不是0号库，选择一下数据库。
            if ($attr['select'] != 0) {
                self::$_instance[$k]->select($attr['select']);
            }
        };
        if (!isset(self::$_instance[$k]) || !(self::$_instance[$k] instanceof self)) {
            $connect($config, $attr, $k);
        } elseif (time() > self::$_instance[$k]->expireTime) {
            self::$_instance[$k]->close();
            $connect($config, $attr, $k);
        }
        return self::$_instance[$k]->redis;
    }

    /**
     * 获取缓存key
     * @param string $key
     * @param mixed ...$args
     * @return string|null
     */
    public static function getCacheKey(string $key, ...$args)
    {
        if (empty($key)) {
            return null;
        }
        $addArgs = '';
        foreach ($args as $arg) {
            if ($arg !== '' && $arg !== false && $arg !== null) {
                $addArgs .= ':' . $arg;
            }
        }
        return self::PREFIX . $key . $addArgs;
    }
}