<?php

namespace Kriss\Nacos;

class Nacos
{
    protected $container;
    /**
     * @var ConfigRepository
     */
    public $config;

    public function __construct(array $config = [])
    {
        //$this->container = $container;
        $this->config = $this->buildConfig($config);
    }

    protected function buildConfig(array $config): ConfigRepository
    {
        $base = require __DIR__ . '/../config/nacos.php';
        return new ConfigRepository(array_merge($base, $config));
    }
}
