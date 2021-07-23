<?php

namespace Jianzhi\Stats\base;

require_once __DIR__ . '/../function.php';

class Base
{
    protected static $chDbCnf  = [];//clickHouse配置
    protected static $redisCnf = [];//redis配置

    public function __construct($config = [])
    {
        if (isset($config['ch_db']) && is_array($config['ch_db'])) {
            self::$chDbCnf = $config['ch_db'];
        }
        if (isset($config['redis']) && is_array($config['redis'])) {
            self::$redisCnf = $config['redis'];
        }
    }

    public function returnData($code = 1000, $msg = 'ok', $data = null)
    {
        $result = [
            'code'      => $code,
            'msg'       => $msg,
            'timestamp' => time(),
            'data'      => $data,
        ];
        return json_encode($result);
    }
}