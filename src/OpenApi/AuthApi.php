<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Response\AccessTokenModel;

class AuthApi extends BaseApi
{
    const LOGIN_URI = '/nacos/v1/auth/login';

    protected $config;
    protected $cache;

    /**
     * 登录
     * @param string $username
     * @param string $password
     * @return AccessTokenModel
     * @throws \Kriss\Nacos\Exceptions\NacosException
     */
    public function login(string $username, string $password): AccessTokenModel
    {
        $result = $this->api(static::LOGIN_URI, [
            'body' => [
                'username' => $username,
                'password' => $password,
            ],
        ], 'POST');
        return new AccessTokenModel($result);
    }
}