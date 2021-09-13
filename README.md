# PHP Nacos

## 安装

`composer require kriss/php-nacos`

## 配置

给 nacos Container 添加各种必须组件

```php
use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Contract\HttpClientInterface;
use Kriss\Nacos\Contract\LoadBalancerManagerInterface;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\Support\HttpClient;
use Kriss\Nacos\Support\LoadBalancerManager;
use Psr\SimpleCache\CacheInterface;

$container = new NacosContainer();

// 构造一个 ConfigRepository 实现 ConfigRepositoryInterface，参考:  Kriss\Nacos\Support\MemoryConfigRepository
// 配置的 nacos 参数参考：config/nacos.php
/** @var ConfigRepositoryInterface $configRepository */
$container->add(ConfigRepositoryInterface::class, $configRepository);

// 构造一个 Httpclient 实现 HttpClientInterface，一般可以直接使用 Kriss\Nacos\Support\HttpClient
$httpClient = new HttpClient($log = new \Psr\Log\NullLogger());
$container->add(HttpClientInterface::class, $httpClient);
// 建议配置 log，记录完整的请求或异常的请求
// 例如使用 monolog：
/*$log = new Logger('nacos');
$log->pushHandler(new StreamHandler(__DIR__ . '/../runtime/mock_log.log'));*/

// 在使用 nacos 授权访问时，需要注入 Psr16 的 Cache 组件，用于缓存 nacos 的令牌
// 以下为例子
/*$filesystemAdapter = new Local(__DIR__ . '/../runtime');
$filesystem = new Filesystem($filesystemAdapter);
$cachePool = new FilesystemCachePool($filesystem);
$simpleCache = new SimpleCacheBridge($cachePool);*/
/** @var CacheInterface $simpleCache */
$container->add(CacheInterface::class, $simpleCache);

// 在使用 InstanceService::getOptimal 时需要 loadBalancer
$loadBalancer = new LoadBalancerManager();
$container->add(LoadBalancerManagerInterface::class, $loadBalancer);
```

## 使用

所有 OpenApi 的使用（包括授权 Auth）

```php
/** @var \Kriss\Nacos\NacosContainer $container */

// 授权 API
$authApi = $container->get(\Kriss\Nacos\OpenApi\AuthApi::class);
// 配置管理 API
$configApi = $container->get(\Kriss\Nacos\OpenApi\ConfigApi::class);
// 服务实例 API
$instanceApi = $container->get(\Kriss\Nacos\OpenApi\InstanceApi::class);
// 命名空间 API
$namespaceApi = $container->get(\Kriss\Nacos\OpenApi\NamespaceApi::class);
// 操作系统 API
$operatorApi = $container->get(\Kriss\Nacos\OpenApi\OperatorApi::class);
// 服务 API
$serviceApi = $container->get(\Kriss\Nacos\OpenApi\ServiceApi::class);
```

常用业务的使用

```php
/** @var \Kriss\Nacos\NacosContainer $container */

// 配置管理
$configService = $container->get(\Kriss\Nacos\Service\ConfigService::class);
// 监听配置修改
while (true) {
    $configService->listenConfigs();
    usleep(5000);
    if ('something') {
        break;
    }
}
// 服务实例 API
$instanceService = $container->get(\Kriss\Nacos\Service\InstanceService::class);
// 注册当前服务实例
$instanceService->register();
// 注销服务实例
$instanceService->deregister();
```

## 扩展

修改已有的服务或组件，通过覆盖原 API 或 Service，然后在容器中注入新的即可，比如： 想要修改 ConfigApi，只需要新建一个类，比如 `MyConfigApi extend ConfigApi`，然后在配置
container 时加入：`$container->add(ConfigApi::class, MyConfigApi::class)`

## 例子

[Laravel](./examples/laravel)