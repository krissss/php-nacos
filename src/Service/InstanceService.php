<?php

namespace Kriss\Nacos\Service;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Contract\LoadBalancerManagerInterface;
use Kriss\Nacos\DTO\Request\InstanceBeatJson;
use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\InstanceBeatModel;
use Kriss\Nacos\DTO\Response\Service\ServiceHostModel;
use Kriss\Nacos\Instance;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\OpenApi\InstanceApi;
use Kriss\Nacos\OpenApi\ServiceApi;
use Kriss\Nacos\Service;
use RuntimeException;

class InstanceService
{
    protected $container;
    protected $instance;
    protected $instanceApi;

    public function __construct(NacosContainer $container)
    {
        $this->container = $container;
        $this->instance = $container->get(Instance::class);
        $this->instanceApi = $container->get(InstanceApi::class);
    }

    /**
     * 注册实例
     * @return bool
     */
    public function register()
    {
        $service = $this->container->get(Service::class);
        $serviceApi = $this->container->get(ServiceApi::class);

        $serviceParams = ServiceParams::loadFromServiceModel($service);
        $exist = !!$serviceApi->detail($serviceParams);
        if (!$exist && !$serviceApi->create($serviceParams)) {
            throw new RuntimeException('服务创建失败: ' . $service->serviceName);
        }

        $instanceParams = InstanceParams::loadFromInstanceModel($this->instance);
        if (!$this->instanceApi->register($instanceParams)) {
            throw new RuntimeException('服务注册实例失败: ' . $this->instance->ip);
        }

        return true;
    }

    /**
     * 注销实例
     * @return bool
     */
    public function deregister()
    {
        $instanceParams = InstanceParams::loadFromInstanceModel($this->instance);
        return $this->instanceApi->unregister($instanceParams);
    }

    private $_beatCached = [];

    /**
     * 发送一次心跳
     * @return InstanceBeatModel
     * @throws RuntimeException
     */
    public function beatOne(): InstanceBeatModel
    {
        if (!$this->_beatCached) {
            $instanceApi = $this->container->get(InstanceApi::class);
            $instanceParams = InstanceParams::loadFromInstanceModel($this->instance);

            $this->_beatCached = [$instanceApi, $instanceParams];
        }
        /** @var InstanceApi $instanceApi */
        /** @var InstanceParams $instanceParams */
        [$instanceApi, $instanceParams] = $this->_beatCached;

        $detail = $instanceApi->detail($instanceParams);
        if (!$detail) {
            throw new RuntimeException("实例不存在: {$instanceParams->getNamespaceId()}:{$instanceParams->getGroupName()}:{$instanceParams->getServiceName()}({$instanceParams->getNamespaceId()}:{$instanceParams->getGroupName()})");
        }
        return $instanceApi->beat(InstanceBeatParams::loadFromInstanceParams($instanceParams));
    }

    /**
     * 注册并触发心跳
     */
    public function registerAndBeat($retryCount = 5, $isRetry = false)
    {
        if (!$isRetry) {
            $is = $this->register();
            if ($is === false) {
                return false;
            }
            $this->beatLog('注册成功，等待10秒后开启心跳');
            sleep(10);
        }

        if ($retryCount <= 0) {
            $this->beatLog('beat over with error');
            return false;
        }
        while (true) {
            try {
                $data = $this->beatOne();
                $this->beatLog('beat');
                usleep($data->clientBeatInterval * 1000);
            } catch (RuntimeException $e) {
                $this->beatLog('beat err: ' . $e->getMessage());
                $this->beatLog('retry: ' . $retryCount);
                sleep(1);
                return $this->registerAndBeat($retryCount - 1, true);
            }
        }
    }

    private function beatLog($msg)
    {
        echo date('Y-m-d H:i:s') . ': ' . $msg . PHP_EOL;
    }

    /**
     * 获取最优实例
     * @param ServiceParams $params
     * @param array $clusters
     * @return false|ServiceHostModel
     */
    public function getOptimal(ServiceParams $params, array $clusters = [])
    {
        $config = $this->container->get(ConfigRepositoryInterface::class);
        $serviceApi = $this->container->get(ServiceApi::class);
        $list = $serviceApi->instanceList($params, $clusters, true);
        if (!$list->hosts) {
            return false;
        }
        $instances = array_filter($list->hosts, function (ServiceHostModel $item) {
            return $item->enabled && $item->healthy;
        });
        $tactics = strtolower($config->get('nacos.load_balancer.default', 'weighted-random'));
        return $this->loadBalancer($instances, $tactics);
    }

    /**
     * @param array|ServiceHostModel[] $nodes
     * @param string $strategy
     * @return ServiceHostModel
     */
    protected function loadBalancer(array $nodes, string $strategy): ServiceHostModel
    {
        $loadNodes = [];
        $nacosNodes = [];
        foreach ($nodes as $node) {
            $key = sprintf('%s:%d', $node->ip, $node->port);
            $loadNodes[$key] = $node->weight;
            $nacosNodes[$key] = $node;
        }

        $loadBalancerManager = $this->container->get(LoadBalancerManagerInterface::class);
        $loadBalancer = $loadBalancerManager->getByName($strategy);
        $loadBalancer->setNodes($loadNodes);
        return $nacosNodes[$loadBalancer->select()];
    }
}