<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\Enums\ServerResponseCode;
use Kriss\Nacos\Exceptions\ServerException;
use Kriss\Nacos\Nacos;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @link https://nacos.io/zh-cn/docs/open-api.html
 */
abstract class BaseApi
{
    /**
     * @var Nacos
     */
    private $nacos;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Nacos $nacos)
    {
        $this->nacos = $nacos;
        $this->logger = new NullLogger();
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * 接口请求
     * @param $uri
     * @param array $options
     * @param string $method
     * @return array|string
     */
    protected function api($uri, $options = [], $method = 'GET')
    {
        $options = array_merge([
            'max_redirects' => 0,
        ], $options);

        $response = $this->getHttpClient()->request(strtoupper($method), $this->buildUrl($uri), $options);
        if (($statusCode = $response->getStatusCode()) !== ServerResponseCode::OK) {
            $this->logger->error('response exception: ' . $response->getContent(false), ['code' => $statusCode]);
            throw new ServerException(ServerResponseCode::getDescription($statusCode), $statusCode);
        }
        try {
            return $response->toArray(false);
        } catch (JsonException $exception) {
            return $response->getContent(false);
        }
    }

    protected $httpClient;

    /**
     * @return \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    protected function getHttpClient()
    {
        if ($this->httpClient) {
            return $this->httpClient;
        }

        $this->httpClient = HttpClient::create();

        return $this->httpClient;
    }

    /**
     * 构建完整的 url 地址
     * @param string $uri
     * @param array $params query 参数
     * @return string
     */
    protected function buildUrl(string $uri, $params = []): string
    {
        $url = rtrim($this->nacos->config->get('baseUri'), '/') . '/' . ltrim($uri, '/');
        if ($params) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
        }
        return $url;
    }
}
