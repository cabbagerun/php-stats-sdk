<?php

namespace Jianzhi\Stats\base;

class ControllerBase extends Base
{
    public function __call($name, $arguments)
    {
        return api_return(1001, '接口不存在');
    }

    //todo 用于公共验证
}