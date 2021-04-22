<?php

namespace Kriss\Nacos\Tests\Support;

use Kriss\Nacos\DTO\Response\AccessTokenModel;
use Kriss\Nacos\Support\AccessToken;
use Kriss\Nacos\Tests\Mocks\Traits\NacosTrait;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    use NacosTrait;

    public function testGet()
    {
        $accessToken = new AccessToken($this->getNacos());
        $model = $accessToken->get();
        $this->assertInstanceOf(AccessTokenModel::class, $model);
        $this->assertIsString($model->accessToken);

        // 再次取值时 token 一致
        $newModel = $accessToken->get();
        $this->assertEquals($model->accessToken, $newModel->accessToken);
    }
}
