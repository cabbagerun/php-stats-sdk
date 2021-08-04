<?php

namespace Jianzhi\Stats\extend;

use Jianzhi\Stats\extend\Request;
use Jianzhi\Stats\extend\SdkException;

trait Init
{
    private $request;
    public function __construct(array $config = [], $remoteRequest = null)
    {
        $this->request = new Request($config, $remoteRequest);
        $this->registerErr();
    }

    private function registerErr()
    {
        error_reporting(E_ALL);
        set_error_handler([(new SdkException()), 'appError']);
    }
}