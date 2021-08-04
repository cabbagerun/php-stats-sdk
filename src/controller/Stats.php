<?php

namespace Jianzhi\Stats\controller;

use Jianzhi\Stats\base\ControllerBase;
use Jianzhi\Stats\extend\MyRedis;
use Jianzhi\Stats\validate\VDataStats;
use Jianzhi\Stats\service\SDataStats;
use Jianzhi\Stats\extend\Cacheable;

class Stats extends ControllerBase
{
    /**
     * @return false|string
     */
    public function select()
    {
        $cacheKey = MyRedis::getCacheKey($this->request()->controller(), $this->request()->action());
        $data = (new Cacheable())->getCache($cacheKey, function () {
            $userId = $this->request()->get('user_id', []);
            $validate = new VDataStats();
            $validate->scene('select');
            if (!$validate->check($this->request()->param('', []))) {
                return json_return(CODE_FAIL, (string)$validate->getError());
            }
            return (new SDataStats())->selectTest($userId);
        });
        return json_return(CODE_SUC, MSG_SUC, [$data, $this->request()->server('HTTP_ORIGIN')]);
    }
}