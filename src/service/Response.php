<?php

namespace Jianzhi\Stats\service;

class Response
{
    public static function apiReturn(int $code = 1000, string $msg = 'ok', $data = null)
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