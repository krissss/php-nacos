<?php

namespace Kriss\Nacos;

use Kriss\Nacos\OpenApi\ConfigApi;
use Kriss\Nacos\OpenApi\InstanceApi;
use Kriss\Nacos\OpenApi\NamespaceApi;
use Kriss\Nacos\OpenApi\OperatorApi;
use Kriss\Nacos\OpenApi\ServiceApi;

/**
 * @method ConfigApi config()
 * @method InstanceApi instance()
 * @method NamespaceApi namespace()
 * @method OperatorApi operator()
 * @method ServiceApi service()
 */
class NacosOpenApi
{
    protected $nacos;

    public function __construct(Nacos $nacos)
    {
        $this->nacos = $nacos;
    }

    protected function allServicesDefined()
    {
        return [
            'config' => ConfigApi::class,
            'instance' => InstanceApi::class,
            'namespace' => NamespaceApi::class,
            'operator' => OperatorApi::class,
            'service' => ServiceApi::class,
        ];
    }

    protected function getService($name)
    {
        $services = $this->allServicesDefined();
        return $services[$name];
    }

    public function __call($name, $arguments)
    {
        $service = static::getService($name);
        return new $service($this->nacos);
    }
}
