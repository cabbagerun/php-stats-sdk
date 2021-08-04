<?php

if (!function_exists('json_return')) {
    /**
     * json响应
     * @param int $code
     * @param string $msg
     * @param null $data
     * @return false|string
     */
    function json_return(int $code = CODE_SUC, string $msg = MSG_SUC, $data = null)
    {
        return \Jianzhi\Stats\extend\Response::jsonReturn($code, $msg, $data);
    }
}

if (!function_exists('jsonp_return')) {
    /**
     * jsonp响应
     * @param string $jsonp
     * @param null $data
     * @return string
     */
    function jsonp_return(string $jsonp, $data = null)
    {
        return \Jianzhi\Stats\extend\Response::jsonpReturn($jsonp, $data);
    }
}

if (!function_exists('cross_return')) {
    /**
     * 跨域响应
     * @param $data
     * @param string $jsonp
     * @param int $code
     * @param string $msg
     * @return false|string
     */
    function cross_return($data, string $jsonp = '', int $code = CODE_SUC, string $msg = MSG_SUC)
    {
        return \Jianzhi\Stats\extend\Response::crossReturn($data, $jsonp, $code, $msg);
    }
}
