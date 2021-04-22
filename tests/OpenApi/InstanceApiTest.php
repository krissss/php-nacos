<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Request\InstanceBeatJson;
use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\InstanceBeatModel;
use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\Exceptions\ServerException;
use Kriss\Nacos\OpenApi\InstanceApi;
use Kriss\Nacos\OpenApi\ServiceApi;
use Kriss\Nacos\Tests\Traits\NacosTrait;
use Kriss\Nacos\Tests\Traits\TestsConfigTrait;
use PHPUnit\Framework\TestCase;

class InstanceApiTest extends TestCase
{
    use NacosTrait;
    use TestsConfigTrait;

    protected $api;
    protected $serviceApi;

    protected function setUp()
    {
        $this->api = new InstanceApi($this->getNacos());
        $this->serviceApi = new ServiceApi($this->getNacos());
    }

    protected function createService($serviceName)
    {
        $this->serviceApi->create(new ServiceParams($serviceName));
    }

    protected function deleteService($serviceName)
    {
        $this->serviceApi->delete(new ServiceParams($serviceName));
    }

    public function testRegister()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        [$ip, $port] = $this->getTestsConfig('create_instance');

        $this->createService($serviceName);

        // 简单注册
        $instanceParams = new InstanceParams($ip, $port, $serviceName);
        $data = $this->api->register($instanceParams);
        $this->assertEquals(true, $data);
        // 检查
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals($ip, $detail->ip);
        $this->assertEquals($port, $detail->port);
        $this->assertStringContainsString($serviceName, $detail->service);

        // 复杂配置
        $instanceParams = (new InstanceParams($ip, $port, $serviceName))
            ->setMetadata(['meta' => 11]);
        $data = $this->api->register($instanceParams);
        $this->assertEquals(true, $data);
        // 检查
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals($ip, $detail->ip);
        $this->assertEquals($port, $detail->port);
        $this->assertStringContainsString($serviceName, $detail->service);
        $this->assertEquals(['meta' => 11], $detail->metadata);

        $this->deleteService($serviceName);
    }

    public function testBeat()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        [$ip, $port] = $this->getTestsConfig('create_instance');

        $this->createService($serviceName);

        $instanceParams = new InstanceParams($ip, $port, $serviceName);
        $this->api->register($instanceParams);
        $detail = $this->api->detail($instanceParams);

        $data = $this->api->beat(new InstanceBeatParams($serviceName, InstanceBeatJson::fromInstanceDetailModel($detail)));
        $this->assertInstanceOf(InstanceBeatModel::class, $data);
        $this->assertEquals(true, $data->lightBeatEnabled);

        $this->deleteService($serviceName);
    }

    public function testUnregister()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        [$ip, $port] = $this->getTestsConfig('create_instance');

        $this->createService($serviceName);

        $instanceParams = new InstanceParams($ip, $port, $serviceName);
        $this->api->register($instanceParams);

        // 注销存在的
        $data = $this->api->unregister($instanceParams);
        $this->assertEquals(true, $data);
        // 检查存在
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals(null, $detail);

        // 注销不存在的
        $data = $this->api->unregister($instanceParams);
        $this->assertEquals(true, $data);

        $this->deleteService($serviceName);
    }

    public function testDetail()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        [$ip, $port] = $this->getTestsConfig('create_instance');

        $this->createService($serviceName);

        $instanceParams = new InstanceParams($ip, $port, $serviceName);
        $this->api->register($instanceParams);

        // 存在实例时
        $detail = $this->api->detail($instanceParams);
        $this->assertInstanceOf(InstanceDetailModel::class, $detail);
        $this->assertIsFloat($detail->weight);
        $this->assertIsString($detail->service);

        $this->api->unregister($instanceParams);
        // 实例不存在时
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals(null, $detail);

        $this->deleteService($serviceName);
    }

    public function testModify()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        [$ip, $port] = $this->getTestsConfig('create_instance');

        $this->createService($serviceName);

        $instanceParams = new InstanceParams($ip, $port, $serviceName);
        $this->api->register($instanceParams);

        $data = $this->api->modify($instanceParams->setMetadata(['meta' => 222]));
        $this->assertEquals(true, $data);
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals(['meta' => 222], $detail->metadata);

        $this->deleteService($serviceName);
    }

    public function testModifyHealth()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        [$ip, $port] = $this->getTestsConfig('create_instance');

        $this->createService($serviceName);

        $instanceParams = new InstanceParams($ip, $port, $serviceName);
        $this->api->register($instanceParams);

        // 新建的为 true
        $detail = $this->api->detail($instanceParams);
        $this->assertEquals(true, $detail->healthy);
        // 修改为 false
        $isHeathCheckIsWorking = $this->getTestsConfig('is_heath_check_working');
        try {
            $data = $this->api->modifyHealth($instanceParams->setHealthy(false));
            $this->assertEquals(true, $data);
            $detail = $this->api->detail($instanceParams);
            $this->assertEquals(false, $detail->healthy);
        } catch (ServerException $e) {
            // 更新实例的健康状态,仅在集群的健康检查关闭时才生效,当集群配置了健康检查时,该接口会返回错误
            if ($isHeathCheckIsWorking) {
                $this->assertStringContainsString('health check is still working', $e->getMessage());
            }
        }

        $this->deleteService($serviceName);
    }
}
