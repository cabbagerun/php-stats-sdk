# Jianzhi php-stats-sdk

- php: ^7.1
- ext-swoole: ^4.4
- ext-curl: *
- ext-json: *

## 测试

``` bash
#启动服务
php test\http.php

#启动消费
php test\task.php

#访问接口
http://localhost:9501/Controller/action
```

## 安装

- composer.jianzhikeji.com 绑定host到测试服

- composer config repo.jianzhi composer http://composer.jianzhikeji.com   

- composer require jianzhi/php-stats-sdk
