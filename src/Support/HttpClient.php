<?php

namespace Kriss\Nacos\Support;

use Kriss\Nacos\Contract\HttpClientInterface;
use Kriss\Nacos\Enums\NacosResponseCode;
use Kriss\Nacos\Exceptions\NacosException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\JsonException;
use Throwable;

class HttpClient implements HttpClientInterface
{
    protected $logger;
    protected $client;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->client = \Symfony\Component\HttpClient\HttpClient::create();
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(string $url, $options = [], $method = 'GET')
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
    public function log($messages, $level = 'debug')
    {
        if (is_array($messages)) {
            $messages = Json::encode($messages);
        } elseif ($messages instanceof Throwable) {
            $messages = $messages->getMessage() . "\n" . $messages->getTraceAsString();
        }
        $this->logger->log($level, $messages, ['from' => __CLASS__]);
    }
}