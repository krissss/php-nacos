<?php

namespace Kriss\Nacos\Support;

use InvalidArgumentException;
use Kriss\Nacos\DTO\Response\AccessTokenModel;
use Kriss\Nacos\Nacos;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpClient\HttpClient;

class AccessToken
{
    /**
     * @var Nacos
     */
    protected $nacos;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var CacheInterface
     */
    protected $cache;

    public function __construct(Nacos $nacos)
    {
        $this->nacos = $nacos;
        $this->username = $nacos->config->get('auth_username');
        $this->password = $nacos->config->get('auth_password');
        if (!$this->username || !$this->password) {
            throw new InvalidArgumentException('auth_username and auth_password 不能为空');
        }
        if (!$nacos->container->has(CacheInterface::class)) {
            throw new InvalidArgumentException('Container 中无 ' . CacheInterface::class . ' 实例');
        }
        $this->cache = $nacos->container->get(CacheInterface::class);
    }

    protected function getCacheKey()
    {
        return 'nacos_auth_' . substr(md5($this->username . $this->password), 8, 16);
    }

    public function get(): AccessTokenModel
    {
        $cacheKey = $this->getCacheKey();
        if (!$this->cache->has($cacheKey)) {
            $response = HttpClient::create()->request('POST', $this->nacos->buildUrl('/nacos/v1/auth/login'), [
                'body' => [
                    'username' => $this->username,
                    'password' => $this->password,
                ],
            ]);
            $data = $response->toArray();
            $model = new AccessTokenModel($data);
            $this->cache->set($cacheKey, $model, $model->tokenTtl);
        }
        return $this->cache->get($cacheKey);
    }
}