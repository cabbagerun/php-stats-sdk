<?php

namespace Jianzhi\Stats;

use Jianzhi\Stats\service\Init;
use Jianzhi\Stats\service\swoole\HttpServer;
use Jianzhi\Stats\service\swoole\TickTask;

/**
 * 对外api访问
 * Class Dispatch
 * @package Jianzhi\Stats
 */
class Dispatch
{
    use Init;

    /**
     * 访问接口
     * @param $class
     * @param $action
     * @return false|mixed|string
     */
    public function callApi($class, $action)
    {
        try {
            $class = ucwords($class);
            $action = ucfirst($action);
            $classPath = __DIR__ . '/controller/' . $class . '.php';
            $class     = '\\Jianzhi\\Stats\\controller\\' . $class;
            if (!is_file($classPath) || !method_exists($class, $action)) {
                return api_return(1001, '接口不存在');
            }
            $obj = new $class($this->request);
            return $obj->$action();
        } catch (\Throwable $e) {
            return api_return(1001, '接口异常' . $e->getMessage());
        }
    }

    /**
     * 开启http服务
     * @throws \Exception
     */
    public function startHttpServer() {
        try {
            $http = new HttpServer($this->request);
            $http->initSet()->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 开启定时任务
     * @throws \Exception
     */
    public function startTickTask()
    {
        try {
            $task = new TickTask($this->request);
            $task->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}