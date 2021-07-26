# Jianzhi php-stats-sdk

- php: ^7.1
- ext-swoole: ^4.4
- ext-curl: *
- ext-json: *

## 测试

``` bash
#启动http服务
php example\http.php

#启动消费
php example\task.php

#模拟Api
php example\api.php

#访问接口
http://localhost:9501/Controller/action
```

## 安装

- composer.jianzhikeji.com 绑定host到服务器

- composer config repo.jianzhi composer http://composer.jianzhikeji.com   

- composer require jianzhi/php-stats-sdk
