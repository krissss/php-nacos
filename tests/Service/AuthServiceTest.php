<?php

namespace Kriss\Nacos\Tests\Service;

use Kriss\Nacos\Service\AuthService;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    use TestSupportTrait;

    protected $service;

    protected function setUp()
    {
        $this->service = $this->getNacos()->get(AuthService::class);
    }

    public function testGetAccessToken()
    {
        $token = $this->service->getAccessToken();
        $this->assertIsString($token);
        // 再次获取时一致
        $token2 = $this->service->getAccessToken();
        $this->assertEquals($token, $token2);
    }
}
