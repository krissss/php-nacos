<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\Enums\ServerResponseCode;
use Kriss\Nacos\Exceptions\ServerException;
use Kriss\Nacos\Nacos;
use Kriss\Nacos\Support\AccessToken;
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

    public function __construct(Nacos $nacos)
    {
        $this->nacos = $nacos;
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
        $this->nacos->log($requestParams = ['type' => 'request', 'uri' => $uri, 'options' => $options, 'method' => $method]);

        $options = array_merge([
            'max_redirects' => 0,
        ], $options);

        foreach (['query', 'body'] as $k) {
            if (isset($options[$k])) {
                $options[$k] = array_filter($options[$k], function ($value) {
                    return $value !== null;
                });
            }
        }
        if ($this->nacos->config->get('auth_enabled')) {
            $options['query']['accessToken'] = (new AccessToken($this->nacos))->get()->accessToken;
        }

        $response = $this->getHttpClient()->request(strtoupper($method), $this->nacos->buildUrl($uri), $options);
        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);

        $this->nacos->log($responseData = ['type' => 'response', 'code' => $statusCode, 'content' => $content]);

        if (($statusCode = $response->getStatusCode()) !== ServerResponseCode::OK) {
            $this->nacos->log(['type' => 'response not ok', 'request' => $requestParams, 'response' => $responseData], 'warning');
            throw new ServerException($content, $statusCode);
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
}
