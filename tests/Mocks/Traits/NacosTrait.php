<?php

namespace Kriss\Nacos\Tests\Mocks\Traits;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use Kriss\Nacos\Nacos;
use League\Container\Container;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

trait NacosTrait
{
    private $nacos;

    protected function getNacos()
    {
        if (!$this->nacos) {
            $container = new Container();

            $log = new Logger('test');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../runtime/mock_log.log'));
            $container->add(LoggerInterface::class, $log);

            $filesystemAdapter = new Local(__DIR__ . '/../runtime');
            $filesystem = new Filesystem($filesystemAdapter);
            $cachePool = new FilesystemCachePool($filesystem);
            $simpleCache = new SimpleCacheBridge($cachePool);
            $container->add(CacheInterface::class, $simpleCache);

            $this->nacos = new Nacos(require __DIR__ . '/config.php', $container);
        }

        return $this->nacos;
    }
}