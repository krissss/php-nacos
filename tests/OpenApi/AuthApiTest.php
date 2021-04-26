<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Response\AccessTokenModel;
use Kriss\Nacos\Enums\NacosResponseCode;
use Kriss\Nacos\Exceptions\NacosException;
use Kriss\Nacos\OpenApi\AuthApi;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class AuthApiTest extends TestCase
{
    use TestSupportTrait;

    public function testLogin()
    {
        $api = $this->getNacos()->get(AuthApi::class);
        // 登录403
        try {
            $api->login('not_exist_user', 'not_exist_password');
        } catch (NacosException $e) {
            $this->assertEquals(NacosResponseCode::FORBIDDEN, $e->getCode());
        }

        // 登录成功
        $accessToken = $api->login($this->getTestConfig('auth_username'), $this->getTestConfig('auth_password'));
        $this->assertInstanceOf(AccessTokenModel::class, $accessToken);
        $this->assertIsString($accessToken->accessToken);
    }
}
