<?php

namespace Kriss\Nacos\Tests\Mocks\Traits;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Contract\HttpClientInterface;
use Kriss\Nacos\Contract\LoadBalancerManagerInterface;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\Support\HttpClient;
use Kriss\Nacos\Support\LoadBalancerManager;
use Kriss\Nacos\Support\MemoryConfigRepository;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\SimpleCache\CacheInterface;

trait TestSupportTrait
{
    private $nacos;

    protected function getNacos()
    {
        if (!$this->nacos) {
            $container = new NacosContainer();

            // configRepository
            $configRepository = new MemoryConfigRepository(require __DIR__ . '/nacos_config.php', []);
            $container->add(ConfigRepositoryInterface::class, $configRepository);

            // httpclient
            $log = new Logger('test');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../runtime/mock_log.log'));
            $httpClient = new HttpClient($configRepository->get('nacos.api.baseUri'), $log);
            $container->add(HttpClientInterface::class, $httpClient);

            // cache
            $filesystemAdapter = new Local(__DIR__ . '/../runtime');
            $filesystem = new Filesystem($filesystemAdapter);
            $cachePool = new FilesystemCachePool($filesystem);
            $simpleCache = new SimpleCacheBridge($cachePool);
            $container->add(CacheInterface::class, $simpleCache);

            // load balancer
            $loadBalancer = new LoadBalancerManager();
            $container->add(LoadBalancerManagerInterface::class, $loadBalancer);

            $this->nacos = $container;
        }

        return $this->nacos;
    }

    protected function getTestConfig($key)
    {
        return $this->getNacos()->get(ConfigRepositoryInterface::class)->get('nacos.tests.' . $key);
    }
}