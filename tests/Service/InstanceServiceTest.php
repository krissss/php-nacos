<?php

namespace Kriss\Nacos\Tests\Service;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\DTO\Response\ServiceDetailModel;
use Kriss\Nacos\OpenApi\InstanceApi;
use Kriss\Nacos\OpenApi\ServiceApi;
use Kriss\Nacos\Service\InstanceService;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class InstanceServiceTest extends TestCase
{
    use TestSupportTrait;

    protected $service;
    protected $config;

    protected function setUp()
    {
        $this->service = $this->getNacos()->get(InstanceService::class);
        $this->config = $this->getNacos()->get(ConfigRepositoryInterface::class);
    }

    public function testRegister()
    {
        $this->service->register();

        $instance = $this->getNacos()->get(InstanceApi::class);
        $detail = $instance->detail(new InstanceParams(
            $this->config->get('nacos.instance.ip'),
            $this->config->get('nacos.instance.port'),
            $this->config->get('nacos.instance.serviceName')
        ));
        $this->assertInstanceOf(InstanceDetailModel::class, $detail);

        // 测试结束清除服务
        $this->service->deregister();
        $this->getNacos()->get(ServiceApi::class)->delete(new ServiceParams($this->config->get('nacos.service.serviceName')));
    }

    public function testDeregister()
    {
        // 直接注销时
        $is = $this->service->deregister();
        $this->assertEquals(true, $is);

        // 新建后注销
        $this->service->register();
        $is = $this->service->deregister();
        $this->assertEquals(true, $is);

        // 注销后服务还在
        $detail = $this->getNacos()->get(ServiceApi::class)->detail(new ServiceParams($this->config->get('nacos.service.serviceName')));
        $this->assertInstanceOf(ServiceDetailModel::class, $detail);

        // 测试结束清除服务
        $this->getNacos()->get(ServiceApi::class)->delete(new ServiceParams($this->config->get('nacos.service.serviceName')));
    }
}
