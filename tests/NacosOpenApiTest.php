<?php

namespace Kriss\Nacos\Tests;

use Kriss\Nacos\NacosOpenApi;
use Kriss\Nacos\Tests\Mocks\Traits\NacosTrait;
use PHPUnit\Framework\TestCase;

class NacosOpenApiTest extends TestCase
{
    use NacosTrait;

    public function test__call()
    {
        $api = new NacosOpenApi($this->getNacos());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\ConfigApi::class, $api->config());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\InstanceApi::class, $api->instance());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\NamespaceApi::class, $api->namespace());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\OperatorApi::class, $api->operator());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\ServiceApi::class, $api->service());
    }
}
