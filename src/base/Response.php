<?php

namespace Jianzhi\Stats\base;

class Response
{
    public static function returnData($code = 1000, $msg = 'ok', $data = null)
    {
        $result = [
            'code'      => $code,
            'msg'       => $msg,
            'timestamp' => time(),
            'data'      => $data,
        ];
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}