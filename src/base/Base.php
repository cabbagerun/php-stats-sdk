<?php

namespace Jianzhi\Stats\base;

use \Jianzhi\Stats\service\Request;

class Base
{
    protected static $swooleHttpCnf  = [];//swooleHttp配置
    protected static $chDbCnf  = [];//clickHouse配置
    protected static $redisCnf = [];//redis配置
    protected $request = null;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $config = $request->getConfig();
        var_dump($config);
        if (isset($config['swoole_http']) && is_array($config['swoole_http'])) {
            self::$swooleHttpCnf = $config['swoole_http'];
        }
        if (isset($config['ch_db']) && is_array($config['ch_db'])) {
            self::$chDbCnf = $config['ch_db'];
        }
        if (isset($config['redis']) && is_array($config['redis'])) {
            self::$redisCnf = $config['redis'];
        }
    }
}