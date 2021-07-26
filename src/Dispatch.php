<?php

namespace Jianzhi\Stats;

use Jianzhi\Stats\service\Config;
use Jianzhi\Stats\service\swoole\HttpServer as HServer;
use Jianzhi\Stats\service\swoole\TickTask as TTask;
use Jianzhi\Stats\base\Response;

/**
 * 对外api访问
 * Class Dispatch
 * @package Jianzhi\Stats
 */
class Dispatch
{
    use Config;

    /**
     * 访问接口
     * @param $class
     * @param $action
     * @param array $params
     * @return false|mixed|string
     */
    public function callApi($class, $action, $params = [])
    {
        try {
            $classPath = __DIR__ . '/controller/' . $class . '.php';
            $class     = '\\Jianzhi\\Stats\\controller\\' . $class;
            if (!is_file($classPath) || !method_exists($class, $action)) {
                return Response::returnData(1001, '接口不存在');
            }
            $obj = new $class($this->getConfig());
            return $obj->$action($params);
        } catch (\Throwable $e) {
            return Response::returnData(1001, '接口异常' . $e->getMessage());
        }
    }

    /**
     * 开启http服务
     * @param string $host
     * @param int $port
     * @param array $option
     * @param int $mode
     * @param int $sockType
     * @throws \Exception
     */
    public function startHttpServer(
        $host = INNER_SWOOLE_HOST,
        $port = INNER_SWOOLE_PORT,
        $option = [],
        $mode = SWOOLE_PROCESS,
        $sockType = SWOOLE_SOCK_TCP
    ) {
        try {
            $http = new HServer($host, $port, $option, $mode, $sockType);
            $http->run();
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
            $http = new TTask($this->getConfig());
            $http->run();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }
    }
}