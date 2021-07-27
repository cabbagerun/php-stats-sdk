<?php

namespace Jianzhi\Stats\service;

use Jianzhi\Stats\service\Request;

trait Init
{
    private $request;
    public function __construct(array $config = [], $remoteRequest = null)
    {
        $this->request = new Request($config, $remoteRequest);
    }
}