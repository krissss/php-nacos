<?php

namespace Kriss\Nacos\Tests;

use Kriss\Nacos\Nacos;
use Kriss\Nacos\NacosOpenApi;
use PHPUnit\Framework\TestCase;

class NacosOpenApiTest extends TestCase
{
    /**
     * @var Nacos
     */
    protected $nacos;

    protected function setUp()
    {
        $this->nacos = new Nacos();
    }

    public function test__call()
    {
        $api = new NacosOpenApi($this->nacos);
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\ConfigApi::class, $api->config());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\InstanceApi::class, $api->instance());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\NamespaceApi::class, $api->namespace());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\OperatorApi::class, $api->operator());
        $this->assertInstanceOf(\Kriss\Nacos\OpenApi\ServiceApi::class, $api->service());
    }
}
