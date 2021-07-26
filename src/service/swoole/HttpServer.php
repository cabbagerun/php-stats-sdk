<?php

namespace Jianzhi\Stats\service\swoole;

use Jianzhi\Stats\base\Response;
use Jianzhi\Stats\base\SwooleBase;

class HttpServer extends SwooleBase
{
    private $host;
    private $port;
    private $mode;
    private $sockType;

    public function __construct(
        $host = INNER_SWOOLE_HOST,
        $port = INNER_SWOOLE_PORT,
        $option = [],
        $mode = SWOOLE_PROCESS,
        $sockType = SWOOLE_SOCK_TCP
    ) {
        $this->host     = $host;
        $this->port     = $port;
        $this->mode     = $mode;
        $this->sockType = $sockType;
        $option         = array_merge(
            [
                'worker_num'            => swoole_cpu_num(),
                'reactor_num'           => swoole_cpu_num(),
                'max_request'           => 4,//每个worker进程数的最大请求数
                'document_root'         => '/',//配置静态文件根目录
                'enable_static_handler' => false,//开启静态文件请求处理功能true|false
                'daemonize'             => false,//是否开启守护进程true|false
                'package_max_length'    => 20 * 1024 * 1024,
                'buffer_output_size'    => 10 * 1024 * 1024,
                'socket_buffer_size'    => 128 * 1024 * 1024,
            ],
            $option
        );
        parent::__construct($this->host, $this->port, $option);
    }

    /**
     * 自定义实例化
     * @param string $host
     * @param int $port
     * @return mixed|\swoole_http_server
     */
    public function instance($host, $port)
    {
        return new \swoole_http_server($host, $port, $this->mode, $this->sockType);
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
        echo "#### onStart ####" . PHP_EOL;
        swoole_set_process_name('swoole_process_server_master');

        echo "SWOOLE " . SWOOLE_VERSION . " 服务已启动" . PHP_EOL;
        echo "master_pid: {$serv->master_pid}" . PHP_EOL;
        echo "manager_pid: {$serv->manager_pid}" . PHP_EOL;
        echo "########" . PHP_EOL . PHP_EOL;
    }

    /**
     * 当管理进程启动时触发此事件
     * @param $serv
     */
    public function onManagerStart($serv)
    {
        // echo "#### onManagerStart ####".PHP_EOL.PHP_EOL;
        swoole_set_process_name('swoole_process_server_manager');
    }

    /**
     * 在子进程启动时触发
     * @param $serv
     * @param $worker_id
     */
    public function onWorkStart($serv, $worker_id)
    {
        // echo "#### onWorkStart ####".PHP_EOL.PHP_EOL;
        swoole_set_process_name('swoole_process_server_worker');

        spl_autoload_register(
            function ($className) {
                $classPath = __DIR__ . '/../../Dispatch.php';
                if (is_file($classPath)) {
                    require "{$classPath}";
                    return;
                }
            }
        );
    }

    /**
     * 接收请求时触发
     * @param $request
     * @param $response
     */
    public function onRequest($request, $response)
    {
        $response->header("Server", "SwooleServer");
        $response->header("Content-Type", "text/html; charset=utf-8");
        $server      = $request->server;
        $path_info   = $server['path_info'];
        $request_uri = $server['request_uri'];
        $params      = array_merge(($request->get ?: []), ($request->post ?: []));

        if ($path_info == '/favicon.ico' || $request_uri == '/favicon.ico') {
            return $response->end();
        }
        $controller = 'Index';
        $method     = 'home';
        if ($path_info != '/') {
            $path_info = explode('/', $path_info);
            if (!is_array($path_info)) {
                $response->status(404);
                $response->end('URL不存在');
            }
            if ($path_info[1] == 'favicon.ico') {
                return;
            }
            $count_path_info = count($path_info);
            if ($count_path_info > 4) {
                $response->status(404);
                $response->end('URL不存在');
            }
            $controller = $path_info[1] ?? $controller;
            $method     = $path_info[2] ?? $method;
        }
        $result       = Response::returnData(1001, '接口不存在');
        $dispatch     = '\\Jianzhi\\Stats\\Dispatch';
        $dispatchCall = 'callApi';
        if (class_exists($dispatch) && method_exists($dispatch, $dispatchCall)) {
            $dispatchOb = new $dispatch(['ch_db' => ['host' => '127.0.0.1']]);
            $result = $dispatchOb->$dispatchCall($controller, $method, $params);
        }

        $response->end($result);
    }
}