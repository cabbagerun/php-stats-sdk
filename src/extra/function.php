<?php

if (!function_exists('api_return')) {
    /**
     * api响应
     * @param int $code
     * @param string $msg
     * @param null $data
     * @return false|string
     */
    function api_return(int $code = 1000, string $msg = 'ok', $data = null)
    {
        return \Jianzhi\Stats\service\Response::apiReturn($code, $msg, $data);
    }
}