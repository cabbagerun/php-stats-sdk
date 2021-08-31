# Jianzhi php-stats-sdk

- php: ^7.1
- ext-swoole: ^4.x
- ext-curl: *
- ext-json: *

## 安装

- composer require jianzhi/php-stats-sdk

## 结构

``` bash
├── Analytics               数据采集
├── ClickHouse              ClickHouse封装
├── Common                  公共类
├── Exception               异常处理
├── Service                 服务类
├── Dispatch.php            入口
```

## 配置

``` bash
$config = [
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

## 例子

``` bash
$config = ['swoole_http' => [
    'host' => '127.0.0.1',
    'port' => 8123,
    'username' => 'default',
    'password' => '',
], 'ch_db' => [], 'redis' => []];
$tick = new Dispatch($config);
$tick->clickHouseOperator()->select(1);

```

## 测试

``` bash
#启动http服务
php example\click_house.php

#启动http服务
php example\http.php

#通过http服务访问接口
http://localhost:9501/Controller/action

#没有启动http服务时，直接访问接口
php example\api.php

#启动消费任务
php example\task.php
```
