<?php

namespace Jianzhi\Stats\base;

use \Jianzhi\Stats\service\Request;

class Base
{
    protected static $request       = null;
    protected static $config        = [];
    protected static $swooleHttpCnf = [];//swooleHttp配置
    protected static $chDbCnf       = [];//clickHouse配置
    protected static $redisCnf      = [];//redis配置

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
}