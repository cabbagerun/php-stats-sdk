<?php

namespace Jianzhi\Stats;

use Jianzhi\Stats\extend\Init;
use Jianzhi\Stats\command\HttpServer;
use Jianzhi\Stats\command\TaskServer;

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
            $class      = ucwords($class);
            $classPath  = __DIR__ . '/controller/' . $class . '.php';
            $controller = '\\Jianzhi\\Stats\\controller\\' . $class;
            if (!is_file($classPath) || !method_exists($controller, $action)) {
                return json_return(CODE_FAIL, MSG_API_NOT_FOUND);
            }
            $this->request->setController($class)->setAction($action);
            $obj = new $controller($this->request);
            return $obj->$action();
        } catch (\Throwable $e) {
            return json_return(CODE_FAIL, $e->getMessage());
        }
    }

    /**
     * 开启http服务
     * @throws \Exception
     */
    public function startHttpServer()
    {
        try {
            $http = new HttpServer($this->request);
            $http->init()->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 开启定时任务
     * @throws \Exception
     */
    public function startTaskServer()
    {
        try {
            $task = new TaskServer($this->request);
            $task->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}