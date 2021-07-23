<?php

namespace Jianzhi\Stats;

use Jianzhi\Stats\base\Base;
use Jianzhi\Stats\base\ControllerBase;

/**
 * 对外api访问
 * Class Dispatch
 * @package Jianzhi\Stats
 */
class Dispatch extends Base
{
    public function call($class, $action, $params = [])
    {
        try {
            $classPath = __DIR__ . '/controller/' . $class . '.php';
            $class     = '\\Jianzhi\\Stats\\controller\\' . $class;
            if (!is_file($classPath) || !method_exists($class, $action)) {
                return $this->returnData(1001, '接口不存在');
            }
            $stats = new $class();
            return $stats->$action();
        } catch (\Throwable $e) {
            return $this->returnData(1001, '接口异常' . $e->getMessage());
        }
    }
}