<?php

namespace Kriss\Nacos\Service;

use InvalidArgumentException;
use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\DTO\Response\AccessTokenModel;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\OpenApi\AuthApi;
use Psr\SimpleCache\CacheInterface;

class AuthService
{
    protected $container;
    protected $cache;

    protected $username;
    protected $password;

    public function __construct(NacosContainer $container)
    {
        $this->container = $container;
        $this->cache = $container->get(CacheInterface::class);
        $config = $this->container->get(ConfigRepositoryInterface::class);
        $this->username = $config->get('nacos.api.authUsername');
        $this->password = $config->get('nacos.api.authPassword');
        if (!$this->username || !$this->password) {
            throw new InvalidArgumentException('配置中无 nacos.api.authUsername 和 nacos.api.authPassword');
        }
    }

    /**
     * @return string
     * @throws \Kriss\Nacos\Exceptions\NacosException
     */
    public function getAccessToken(): string
    {
        $cacheKey = $this->getCacheKey($this->username, $this->password);
        if (!$this->cache->has($cacheKey)) {
            $model = $this->container->get(AuthApi::class)->login($this->username, $this->password);
            $this->cache->set($cacheKey, $model, $model->tokenTtl);
        }
        /** @var AccessTokenModel $token */
        $token = $this->cache->get($cacheKey);
        return $token->accessToken;
    }

    protected function getCacheKey($username, $password)
    {
        return 'nacos_auth_' . substr(md5($username . $password), 8, 16);
    }
}
