<?php

namespace Jianzhi\Stats\base;

use \Jianzhi\Stats\extend\Request;

class Base
{
    private static $request       = null;
    private static $config        = [];
    private static $swooleHttpCnf = [];//swooleHttp配置
    private static $chDbCnf       = [];//clickHouse配置
    private static $redisCnf      = [];//redis配置

    public function __construct(Request $request = null)
    {
        if ($request) {
            self::$request = $request;
            self::$config  = $request->getConfig();
            if (isset(self::$config['swoole_http']) && is_array(self::$config['swoole_http'])) {
                self::$swooleHttpCnf = self::$config['swoole_http'];
            }
            if (isset(self::$config['ch_db']) && is_array(self::$config['ch_db'])) {
                self::$chDbCnf = self::$config['ch_db'];
            }
            if (isset(self::$config['redis']) && is_array(self::$config['redis'])) {
                self::$redisCnf = self::$config['redis'];
            }
        }
    }

    public function request()
    {
        return self::$request;
    }

    public function config()
    {
        return self::$config;
    }

    public function swooleHttpConfig()
    {
        return self::$swooleHttpCnf;
    }

    public function chDbConfig()
    {
        return self::$chDbCnf;
    }

    public function redisConfig()
    {
        return self::$redisCnf;
    }
}