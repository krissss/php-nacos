<?php

namespace Kriss\Nacos\Support;

use Kriss\Nacos\Contract\HttpClientInterface;
use Kriss\Nacos\Enums\NacosResponseCode;
use Kriss\Nacos\Exceptions\NacosException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpClient\HttpClient as SymfonyHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface as SymfonyHttpClientInterface;
use Throwable;

class HttpClient implements HttpClientInterface
{
    protected LoggerInterface $logger;
    protected SymfonyHttpClientInterface $client;

    public function __construct(LoggerInterface $logger)
    {
        if (!class_exists('Symfony\Component\HttpClient\HttpClient')) {
            throw new \InvalidArgumentException('先安装依赖：composer require symfony/http-client');
        }

        $this->logger = $logger;
        $this->client = SymfonyHttpClient::create();
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(string $url, array $options = [], string $method = 'GET')
    {
        $this->log($requestParams = ['type' => 'request', 'uri' => $url, 'options' => $options, 'method' => $method]);

        $options = array_merge([
            'parse_response_json' => true, // 是否解析 response 的 json
            'max_redirects' => 0,
        ], $options);

        foreach (['query', 'body'] as $k) {
            if (isset($options[$k])) {
                $options[$k] = array_filter($options[$k], function ($value) {
                    return $value !== null;
                });
            }
        }

        $parseResponseJson = $options['parse_response_json'];
        unset($options['parse_response_json']);

        $response = $this->client->request(strtoupper($method), $url, $options);

        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);

        $this->log($responseData = ['type' => 'response', 'code' => $statusCode, 'content' => $content]);

        if (($statusCode = $response->getStatusCode()) !== NacosResponseCode::OK) {
            $this->log(['type' => 'response not ok', 'request' => $requestParams, 'response' => $responseData], 'warning');
            throw new NacosException($content, $statusCode);
        }

        if (!$parseResponseJson) {
            return $content;
        }
        try {
            return $response->toArray(false);
        } catch (JsonException $exception) {
            return $content;
        }
    }

    /**
     * @param mixed $messages
     * @param string $level
     */
    public function log($messages, string $level = 'debug')
    {
        if (is_array($messages)) {
            $messages = Json::encode($messages);
        } elseif ($messages instanceof Throwable) {
            $messages = $messages->getMessage() . "\n" . $messages->getTraceAsString();
        }
        $this->logger->log($level, $messages, ['from' => __CLASS__]);
    }
}