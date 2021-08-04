<?php

namespace Jianzhi\Stats\extend;

use Jianzhi\Stats\extend\Log;

class SdkException
{
    /**
     * Error Handler
     * @access public
     * @param  integer $errno   错误编号
     * @param  integer $errstr  详细错误信息
     * @param  string  $errfile 出错的文件
     * @param  integer $errline 出错行号
     * @throws \ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        if (error_reporting() & $errno) {
            $date = date('Y-m-d H:i:s');
            switch ($errno) {
                case E_USER_ERROR:
                    $errType = 'Error';
                    break;
                case E_USER_WARNING:
                case E_WARNING:
                    $errType = 'Warning';
                    break;
                case E_USER_NOTICE:
                case E_NOTICE:
                    $errType = 'Notice';
                    break;
                default:
                    $errType = 'Unknown error';
                    break;
            }
            $err = "[{$errType}] [{$errno}]{$errstr}:[{$errfile}:{$errline}]";
            if (!SDK_DEBUG) {
                $err = "[{$date}]" . $err . PHP_EOL;
                (new Log())->save($err, 'log');
                throw new \ErrorException(MSG_EXCEPTION);
            } else {
                throw new \ErrorException($err);
            }
        }
    }


}