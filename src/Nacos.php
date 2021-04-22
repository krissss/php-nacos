<?php

namespace Kriss\Nacos;

use Kriss\Nacos\Support\Json;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Nacos
{
    /**
     * @var ContainerInterface
     */
    public $container;
    /**
     * @var ConfigRepository
     */
    public $config;

    public function __construct(array $config, ContainerInterface $container)
    {
        $this->config = $this->buildConfig($config);
        $this->container = $container;
    }

    protected function buildConfig(array $config): ConfigRepository
    {
        $base = require __DIR__ . '/../config/nacos.php';
        return new ConfigRepository(array_merge($base, $config));
    }

    /**
     * 构建完整的 url 地址
     * @param string $uri
     * @param array $params
     * @return string
     */
    public function buildUrl(string $uri, array $params = [])
    {
        $url = rtrim($this->config->get('baseUri'), '/') . '/' . ltrim($uri, '/');
        if ($params) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
        }
        return $url;
    }

    /**
     * @var false|LoggerInterface
     */
    private $_logger = false;

    /**
     * 记录日志
     * @param mixed $messages
     * @param string $level
     */
    public function log($messages, $level = 'debug')
    {
        if ($this->_logger === false) {
            if ($this->container->has(LoggerInterface::class)) {
                $this->_logger = $this->container->get(LoggerInterface::class);
            } else {
                $this->_logger = null;
            }
        }
        if ($this->_logger) {
            if (is_array($messages)) {
                $messages = Json::encode($messages);
            } elseif ($messages instanceof Throwable) {
                $messages = $messages->getMessage() . "\n" . $messages->getTraceAsString();
            }
            $this->_logger->log($level, $messages);
        }
    }
}
