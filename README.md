# Jianzhi php-stats-sdk

- php: ^7.1
- ext-swoole: ^4.x
- ext-curl: *
- ext-json: *

## 结构

``` bash
├── base                    基础
├── command                 命令行
├── common                  公共的自定义
├── controller              控制器
├── extend                  扩展
    ├── Cacheable.php       cache服务
    ├── Init.php            Api初始化服务
    ├── Log.php             日志
    ├── MyRedis.php         redis服务
    ├── Request.php         请求服务
    ├── Response.php        响应服务
    ├── SdkException.php    sdk异常处理
├── model                   模型
├── runtime                 运行时日志
├── service                 业务逻辑
├── validate                自动验证目录
├── Dispatch.php            入口
```

## 配置

``` bash
$config = [
    // 调试模式
    'sdk_debug' => false,
    // 日志目录
    'sdk_log_dir' => __DIR__ . '/../runtime',
    // swooleHttp服务
    'swoole_http' => [
        'host' => '127.0.0.1',
        'port' => 9501,
        'option' => [],
    ],
    // clickHouse服务
    'ch_db' => [
       'host' => '127.0.0.1',
       'port' => 9501,
       'username' => 'default',
       'password' => '',
       'db' => 'default',
   ],
    // redis服务
    'redis' => [
       'host' => '127.0.0.1',
       'port' => 6379,
       'password' => '',
   ],
];
```

## 测试

``` bash
#启动http服务
php example\http.php

#通过http服务访问接口
http://localhost:9501/Controller/action

#没有启动http服务时，直接访问接口
php example\api.php

#启动消费任务
php example\task.php
```

## 安装

- composer require jianzhi/php-stats-sdk
