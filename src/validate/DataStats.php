<?php

namespace Jianzhi\Stats\validate;

use think\Validate;

class DataStats extends Validate
{
    protected $rule = [
        'user_id' => 'require',
        'union_id' => 'require',
    ];

    protected $message = [
        'user_id.require' => ':userId必须',
        'union_id.require' => ':unionId必须',
    ];

    protected $scene = [
        'select' => ['user_id'],
        'putData' => ['user_id', 'union_id'],
    ];
}