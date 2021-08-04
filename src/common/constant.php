<?php
// 日志级别
!defined('SDK_LOG_LEVEL') && define('SDK_LOG_LEVEL', [
    'log',
    'swoole',
]);

// 接口状态码
!defined('CODE_SUC') && define('CODE_SUC', 1000);//成功
!defined('CODE_FAIL') && define('CODE_FAIL', 1001);//失败
!defined('CODE_PARAM_ERR') && define('CODE_PARAM_ERR', 1002);//参数错误

// 接口状态信息
!defined('MSG_SUC') && define('MSG_SUC', '成功');
!defined('MSG_FAIL') && define('MSG_FAIL', '失败');
!defined('MSG_PARAM_ERR') && define('MSG_PARAM_ERR', '参数错误');
!defined('MSG_API_NOT_FOUND') && define('MSG_API_NOT_FOUND', '接口不存在');
!defined('MSG_EXCEPTION') && define('MSG_EXCEPTION', '系统异常');
