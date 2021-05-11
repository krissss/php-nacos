<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Contract\HttpClientInterface;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\Support\HttpClient;
use Kriss\Nacos\Support\MemoryConfigRepository;
use Psr\SimpleCache\CacheInterface;

class NacosServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->singleton('nacos', function (Application $app) {
            $container = new NacosContainer();

            // 构造一个 ConfigRepository 实现 ConfigRepositoryInterface，参考:  Kriss\Nacos\Support\MemoryConfigRepository
            // 配置的 nacos 参数参考：config/nacos.php
            $configRepository = new MemoryConfigRepository($app['config']['nacos']);
            $container->add(ConfigRepositoryInterface::class, $configRepository);

            // 构造一个 Httpclient 实现 HttpClientInterface，一般可以直接使用 Kriss\Nacos\Support\HttpClient
            $log = $app['log']; // 全量日志
            //$log = $app['log']->channel('nacos'); // 指定日志，可以配置 level
            $httpClient = new HttpClient($configRepository->get('nacos.api.baseUri'), $log);
            $container->add(HttpClientInterface::class, $httpClient);

            // 在使用 nacos 授权访问时，需要注入 Psr16 的 Cache 组件，用于缓存 nacos 的令牌
            $container->add(CacheInterface::class, $app['cache']);

            return $container;
        });

        $this->app->alias('nacos', NacosContainer::class);
    }

    public function provides()
    {
        return [
            'nacos',
            NacosContainer::class,
        ];
    }
}