<?php

namespace Jianzhi\Stats\base;

class ControllerBase extends Base
{
    public function __call($name, $arguments)
    {
        return json_return(CODE_FAIL, MSG_API_NOT_FOUND);
    }

    //todo 签名验证 -- 性能考虑，简单的AES或JWT
}