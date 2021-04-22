<?php

namespace Kriss\Nacos\Tests\Traits;

use Kriss\Nacos\ConfigRepository;

trait TestsConfigTrait
{
    private $config;

    public function getTestsConfig($key, $default = null)
    {
        if (!$this->config) {
            $items = require __DIR__ . '/config.php';
            $this->config = new ConfigRepository($items['tests']);
        }

        return $this->config->get($key, $default);
    }
}