<?php

namespace Jianzhi\Stats\extend;

class Log
{
    /**
     * @param $msg
     * @param string $flag
     * @return bool
     */
    public function save(string $msg, string $flag = 'log')
    {
        if (defined('SDK_LOG_LEVEL') && is_array(SDK_LOG_LEVEL) && in_array($flag, SDK_LOG_LEVEL)) {
            $logDir = SDK_LOG_DIR . '/' . $flag . '/' . date('Ym');
            if (!is_dir($logDir)) {
                mkdir($logDir);
            }
            $logFile = $logDir . '/' . date('d') . '.log';
            file_put_contents($logFile, $msg, FILE_APPEND);
            return true;
        }
        return false;
    }
}