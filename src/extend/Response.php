<?php

namespace Jianzhi\Stats\extend;

class Response
{
    /**
     * json输出
     * @param int $code
     * @param string $msg
     * @param null $data
     * @return false|string
     */
    public static function jsonReturn(int $code = CODE_SUC, string $msg = MSG_SUC, $data = null)
    {
        $result = [
            'code'      => $code,
            'msg'       => $msg,
            'timestamp' => time(),
            'data'      => $data,
        ];
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * jsonp输出
     * @param $data
     * @param $jsonp
     * @return string
     */
    public static function jsonpReturn(string $jsonp, $data = null)
    {
        $jsonpParam = '';
        if ($data) {
            if (is_object($data)) {
                $data = (array)$data;
            }
            if (is_array($data)) {
                $jsonpParam = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        return $jsonp . '(' . $jsonpParam . ')';
    }

    /**
     * 跨域输出
     * @param $data
     * @param string $jsonp
     * @param int $code
     * @param string $msg
     * @return false|string
     */
    public static function crossReturn($data, string $jsonp = '', int $code = CODE_SUC, string $msg = MSG_SUC)
    {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if ($origin) {
            $allow = [
                'https://h5-dsh.jianzhikeji.com',
                'https://h5-dsh.jianzhikeji.net',
                'https://h5-dsh.jianzhixueyuan.net',
                'https://h5-dsh.jianzhiweike.cn',
                'https://h5-dsh.jianzhiweike.net',
                'https://h5-jxz.jianzhiweike.net',
                'https://h5-jxz.jianzhiweike.cn',
                'https://h5-jxz.jianzhikeji.com',
                'https://h5-jxz.jianzhiweike.com',
                'https://h5-jxz.jianzhixueyuan.net',
                'https://zc.jianzhikeji.net',
                'https://jz-work.com',
            ];
            if (in_array($origin, $allow)) {
                header('Access-Control-Allow-Origin: ' . $origin);
            } elseif (SDK_DEBUG) {
                header('Access-Control-Allow-Origin: *');
            }
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 2592000'); // 预请求时间 30天
            header('Access-Control-Allow-Methods: GET,POST');
            header('Access-Control-Allow-Headers: Content-Type,X-Requested-With');
        }
        if ($jsonp) {
            return self::jsonpReturn($jsonp, $data);
        }
        return self::jsonReturn($code, $msg, $data);
    }
}