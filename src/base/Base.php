<?php

namespace Jianzhi\Stats\base;

use \Jianzhi\Stats\service\Request;

class Base
{
    protected static $swooleHttpCnf = [];//swooleHttp配置
    protected static $chDbCnf       = [];//clickHouse配置
    protected static $redisCnf      = [];//redis配置
    protected        $request       = null;
    protected        $config        = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->config  = $request->getConfig();
        if (isset($this->config['swoole_http']) && is_array($this->config['swoole_http'])) {
            self::$swooleHttpCnf = $this->config['swoole_http'];
        }
        if (isset($this->config['ch_db']) && is_array($this->config['ch_db'])) {
            self::$chDbCnf = $this->config['ch_db'];
        }
        if (isset($this->config['redis']) && is_array($this->config['redis'])) {
            self::$redisCnf = $this->config['redis'];
        }
    }
}