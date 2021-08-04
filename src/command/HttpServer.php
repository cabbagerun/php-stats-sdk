<?php

namespace Jianzhi\Stats\command;

use Jianzhi\Stats\base\SwooleBase;

class HttpServer extends SwooleBase
{
    /**
     * 设置选项
     * @return array|mixed
     */
    public function setOption()
    {
        return [
            'host'   => INNER_SWOOLE_HOST,
            'port'   => INNER_SWOOLE_PORT,
            'option' => [
                'worker_num'            => swoole_cpu_num(),
                'reactor_num'           => swoole_cpu_num(),
                // 'max_request'           => 40,//每个worker进程数的最大请求数
                // 'max_wait_time'         => 3,//收到请求结束的最大等待时间
                'document_root'         => '/',//配置静态文件根目录
                'enable_static_handler' => false,//开启静态文件请求处理功能true|false
                'daemonize'             => false,//是否开启守护进程true|false
                'package_max_length'    => 20 * 1024 * 1024,
                'buffer_output_size'    => 10 * 1024 * 1024,
                'socket_buffer_size'    => 128 * 1024 * 1024,
            ]
        ];
    }

    /**
     * 自定义实例化
     * @param string $host
     * @param int $port
     * @return mixed|\swoole_http_server
     */
    public function instance(string $host, int $port)
    {
        return new \swoole_http_server($host, $port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
    }

    /**
     * 回调
     * @param $serv
     * @return mixed|void
     */
    public function callbacks($serv)
    {
        $serv->on('Start', [$this, 'onStart']);
        $serv->on('WorkerStart', [$this, 'onWorkStart']);
        $serv->on('ManagerStart', [$this, 'onManagerStart']);
        $serv->on("Request", [$this, 'onRequest']);
    }

    /**
     * 启动后在主进程（master）的主线程回调此函数
     * @param $serv
     */
    public function onStart($serv)
    {
        swoole_set_process_name('[stats]swoole_process_server_master');
        echo "#### Server started ####" . PHP_EOL;
        echo "Master pid: {$serv->master_pid}" . PHP_EOL;
        echo "Manager pid: {$serv->manager_pid}" . PHP_EOL . PHP_EOL;
    }

    /**
     * 当管理进程启动时触发此事件
     * @param $serv
     */
    public function onManagerStart($serv)
    {
        swoole_set_process_name('[stats]swoole_process_server_manager');
    }

    /**
     * 在子进程启动时触发
     * @param $serv
     * @param $worker_id
     */
    public function onWorkStart($serv, $worker_id)
    {
        swoole_set_process_name('[stats]swoole_process_server_worker');
        // spl_autoload_register(
        //     function ($className) {
        //         $classPath = __DIR__ . '/../../Dispatch.php';
        //         if (is_file($classPath)) {
        //             require_once "{$classPath}";
        //             return;
        //         }
        //     }
        // );
    }

    /**
     * 接收请求时触发
     * @param \Swoole\Http\Request $request
     * @param \Swoole\Http\Response $response
     * @return mixed
     */
    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        $response->header('Server', 'SwooleServer');
        $response->header('Content-Type', 'application/json; charset=utf-8');
        $server      = $request->server ?: [];
        $path_info   = $server['path_info'] ?? '';
        $request_uri = $server['request_uri'] ?? '';

        $result  = json_return(CODE_FAIL, MSG_API_NOT_FOUND);
        $favicon = '/favicon.ico';
        if ($path_info == $favicon || $request_uri == $favicon) {
            return $response->end($result);
        }
        $controller = $method = '';
        if ($path_info != '/') {
            $path_info = explode('/', $path_info);
            if (!is_array($path_info) || count($path_info) > 4) {
                $response->end($result);
            }
            $controller = $path_info[1] ?? $controller;
            $method     = $path_info[2] ?? $method;
        }
        if (!$controller || !$method) {
            $response->end($result);
        }
        $dispatch = '\\Jianzhi\\Stats\\Dispatch';
        $call     = 'callApi';
        if (class_exists($dispatch) && method_exists($dispatch, $call)) {
            $dispatchOb = new $dispatch($this->config(), $request);
            $result     = $dispatchOb->$call($controller, $method);
        }
        $response->end($result);
    }
}