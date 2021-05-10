<?php

namespace Kriss\Nacos\Service;

use Kriss\Nacos\DTO\Request\InstanceBeatJson;
use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\InstanceBeatModel;
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
            $service = $this->container->get(Service::class);
            $instanceApi = $this->container->get(InstanceApi::class);
            $instanceParams = InstanceParams::loadFromInstanceModel($this->instance);
            $serviceParams = ServiceParams::loadFromServiceModel($service);

            $this->_beatCached = [$instanceApi, $instanceParams, $serviceParams];
        }
        [$instanceApi, $instanceParams, $serviceParams] = $this->_beatCached;

        $detail = $instanceApi->detail($instanceParams);
        if (!$detail) {
            throw new RuntimeException("实例不存在: {$instanceParams->getIp()}:{$instanceParams->getPort()}@{$instanceParams->getServiceName()}");
        }
        return $instanceApi->beat(new InstanceBeatParams($serviceParams->getServiceName(), InstanceBeatJson::fromInstanceDetailModel($detail)));
    }

    /**
     * 注册并触发心跳
     */
    public function registerAndBeat($retryCount = 5)
    {
        $this->register();

        if ($retryCount <= 0) {
            echo date('Y-m-d H:i:s') . ': beat over' . PHP_EOL;
        }

        while (true) {
            try {
                $data = $this->beatOne();
                echo date('Y-m-d H:i:s') . ': beat' . PHP_EOL;
                usleep($data->clientBeatInterval);
            } catch (RuntimeException $e) {
                echo date('Y-m-d H:i:s') . ': beat err: ' . $e->getMessage() . PHP_EOL;
                echo date('Y-m-d H:i:s') . ': retry: ' . $retryCount . PHP_EOL;
                $this->registerAndBeat($retryCount - 1);
            }
        }
    }
}