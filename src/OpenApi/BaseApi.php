<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Contract\HttpClientInterface;
use Kriss\Nacos\Exceptions\NacosException;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\Service\AuthService;

/**
 * @link https://nacos.io/zh-cn/docs/open-api.html
 */
abstract class BaseApi
{
    /**
     * @var NacosContainer
     */
    protected $container;
    /**
     * @var HttpClientInterface
     */
    protected $client;
    /**
     * @var ConfigRepositoryInterface
     */
    protected $config;

    public function __construct(NacosContainer $container)
    {
        $this->container = $container;
        $this->config = $this->container->get(ConfigRepositoryInterface::class);
        $this->client = $this->container->get(HttpClientInterface::class);
    }

    /**
     * 接口请求
     * @param string $uri
     * @param array $options
     * @param string $method
     * @return mixed
     * @throws NacosException
     */
    protected function api(string $uri, array $options = [], string $method = 'GET')
    {
        if ($uri !== AuthApi::LOGIN_URI && $this->config->get('nacos.api.authEnable')) {
            $options['query']['accessToken'] = $this->container->get(AuthService::class)->getAccessToken();
        }

        return $this->client->sendRequest($this->buildUrl($uri), $options, $method);
    }

    /**
     * @param string $uri
     * @return string
     */
    protected function buildUrl(string $uri)
    {
        return rtrim($this->config->get('nacos.api.baseUri'), '/')
            . '/'
            . ltrim($uri, '/');
    }
}
