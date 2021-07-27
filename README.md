# Jianzhi php-stats-sdk

- php: ^7.1
- ext-swoole: ^4.4
- ext-curl: *
- ext-json: *

## 结构

``` bash
├── base            基础目录
├── controller      控制器目录
├── extra           扩展目录
├── log             日志木目录，暂用于搜集swoole http服务日志
├── model           对应数据表的模型目录
├── service         service服务，一般用于处理业务逻辑或服务封装
    ├── ch          clickHouse业务目录
    ├── redis       redis服务
    ├── swoole      swoole服务
├── validate        验证目录
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

- composer.jianzhikeji.com 绑定host到服务器

- composer config repo.jianzhi composer http://composer.jianzhikeji.com   

- composer require jianzhi/php-stats-sdk
