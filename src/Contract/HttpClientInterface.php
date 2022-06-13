<?php

namespace Kriss\Nacos\Contract;

use Kriss\Nacos\Exceptions\NacosException;

interface HttpClientInterface
{
    /**
     * @param string $url
     * @param array $options
     * @param string $method
     * @return mixed
     * @throws NacosException
     */
    public function sendRequest(string $url, array $options = [], string $method = 'GET');
}