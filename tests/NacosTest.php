<?php

namespace Kriss\Nacos\Tests;

use Kriss\Nacos\Tests\Mocks\Traits\NacosTrait;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class NacosTest extends TestCase
{
    use NacosTrait;

    public function test__construct()
    {
        $nacos = $this->getNacos();
        $this->assertEquals('test_value', $nacos->config->get('tests.test_key'));
        $this->assertInstanceOf(LoggerInterface::class, $nacos->container->get(LoggerInterface::class));
        $this->assertInstanceOf(CacheInterface::class, $nacos->container->get(CacheInterface::class));
    }
}
