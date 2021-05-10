<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Request\InstanceBeatJson;
use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\InstanceBeatModel;
use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\Exceptions\NacosException;
use Kriss\Nacos\OpenApi\InstanceApi;
use Kriss\Nacos\OpenApi\ServiceApi;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class InstanceApiTest extends TestCase
{
    use TestSupportTrait;

    protected $api;
    protected $serviceApi;

    protected $serviceName;
    protected $instanceParams;

    protected function setUp()
    {
        $this->api = $this->getNacos()->get(InstanceApi::class);
        $this->serviceApi = $this->getNacos()->get(ServiceApi::class);

        // 创建 service
        $this->serviceName = $this->getTestConfig('create_service_name');
        $this->serviceApi->create(new ServiceParams($this->serviceName));
        // 简单的 instance 配置
        [$ip, $port] = $this->getTestConfig('create_instance');
        $this->instanceParams = new InstanceParams($ip, $port, $this->serviceName);
    }

    protected function tearDown()
    {
        // 删除 service
        $this->serviceApi->delete(new ServiceParams($this->serviceName));
    }


    public function testRegister()
    {
        // 简单注册
        $data = $this->api->register($this->instanceParams);
        $this->assertEquals(true, $data);
        // 检查
        $detail = $this->api->detail($this->instanceParams);
        $this->assertEquals($this->instanceParams->getIp(), $detail->ip);
        $this->assertEquals($this->instanceParams->getPort(), $detail->port);
        $this->assertStringContainsString($this->serviceName, $detail->service);

        // 复杂配置
        $instanceParams = $this->instanceParams
            ->setMetadata(['meta' => 11]);
        $data = $this->api->register($instanceParams);
        $this->assertEquals(true, $data);
        // 检查
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals($this->instanceParams->getIp(), $detail->ip);
        $this->assertEquals($this->instanceParams->getPort(), $detail->port);
        $this->assertStringContainsString($this->serviceName, $detail->service);
        $this->assertEquals(['meta' => 11], $detail->metadata);
    }

    public function testBeat()
    {
        $this->api->register($this->instanceParams);
        $detail = $this->api->detail($this->instanceParams);

        $data = $this->api->beat(new InstanceBeatParams($this->serviceName, InstanceBeatJson::fromInstanceDetailModel($detail)));
        $this->assertInstanceOf(InstanceBeatModel::class, $data);
        $this->assertEquals(true, $data->lightBeatEnabled);
        $this->assertIsInt($data->clientBeatInterval);

        // 不存在的实例自动创建
        $data = $this->api->beat((new InstanceBeatParams('not_exist_service', new InstanceBeatJson('127.0.0.1', '21251', 'not_exist_service')))->setEphemeral(false));
        $this->assertInstanceOf(InstanceBeatModel::class, $data);
        $detail = $this->api->detail(new InstanceParams('127.0.0.1', '21251', 'not_exist_service'));
        $this->assertInstanceOf(InstanceDetailModel::class, $detail);
        // 清理
        $this->serviceApi->delete(new ServiceParams('not_exist_service'));
    }

    public function testUnregister()
    {
        $this->api->register($this->instanceParams);

        // 注销存在的
        $data = $this->api->unregister($this->instanceParams);
        $this->assertEquals(true, $data);
        // 检查存在
        $detail = $this->api->detail($this->instanceParams);
        $this->assertEquals(null, $detail);

        // 注销不存在的
        $data = $this->api->unregister($this->instanceParams);
        $this->assertEquals(true, $data);
    }

    public function testDetail()
    {
        $this->api->register($this->instanceParams);

        // 存在实例时
        $detail = $this->api->detail($this->instanceParams);
        $this->assertInstanceOf(InstanceDetailModel::class, $detail);
        $this->assertIsFloat($detail->weight);
        $this->assertIsString($detail->service);

        // 实例不存在时
        $this->api->unregister($this->instanceParams);
        $detail = $this->api->detail($this->instanceParams);
        $this->assertEquals(null, $detail);
        // 服务不存在时
        $this->serviceApi->delete(new ServiceParams($this->serviceName));
        $detail = $this->api->detail($this->instanceParams);
        $this->assertEquals(null, $detail);
    }

    public function testModify()
    {
        $this->api->register($this->instanceParams);

        $data = $this->api->modify($this->instanceParams->setMetadata(['meta' => 222]));
        $this->assertEquals(true, $data);
        $detail = $this->api->detail($this->instanceParams);
        $this->assertEquals(['meta' => 222], $detail->metadata);
    }

    public function testModifyHealth()
    {
        $this->api->register($this->instanceParams);

        // 新建的为 true
        $detail = $this->api->detail($this->instanceParams);
        $this->assertEquals(true, $detail->healthy);
        // 修改为 false
        $isHeathCheckIsWorking = $this->getTestConfig('is_heath_check_working');
        try {
            $data = $this->api->modifyHealth($this->instanceParams->setHealthy(false));
            $this->assertEquals(true, $data);
            $detail = $this->api->detail($this->instanceParams);
            $this->assertEquals(false, $detail->healthy);
        } catch (NacosException $e) {
            // 更新实例的健康状态,仅在集群的健康检查关闭时才生效,当集群配置了健康检查时,该接口会返回错误
            if ($isHeathCheckIsWorking) {
                $this->assertStringContainsString('health check is still working', $e->getMessage());
            }
        }
    }
}
