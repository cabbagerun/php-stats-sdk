<?php

namespace Jianzhi\Stats\validate;

use think\Validate;

class DataStats extends Validate
{
    protected $rule =   [
        'name'  => 'require|max:5',
    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过5个字符',
    ];
}