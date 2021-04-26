<?php

namespace Kriss\Nacos\Service;

use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
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
}