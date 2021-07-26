<?php

namespace Jianzhi\Stats\service;

trait Config
{
    private $config = [];

    public function __construct($config = [])
    {
        $this->loadFiles();
        $this->config = $config;
    }

    private function loadFiles()
    {
        $extra = glob(__DIR__ . '/../extra/*.php');
        foreach ($extra as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setConfig($config = [])
    {
        return $this->config = $config;
    }
}