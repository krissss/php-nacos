<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Request\PageParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\Service\ServiceHostModel;
use Kriss\Nacos\DTO\Response\ServiceInstanceListModel;
use Kriss\Nacos\DTO\Response\ServiceListModel;
use Kriss\Nacos\OpenApi\ServiceApi;
use Kriss\Nacos\Tests\Mocks\Traits\NacosTrait;
use Kriss\Nacos\Tests\Mocks\Traits\TestsConfigTrait;
use PHPUnit\Framework\TestCase;

class ServiceApiTest extends TestCase
{
    use NacosTrait;
    use TestsConfigTrait;

    protected $api;

    protected function setUp()
    {
        $this->api = new ServiceApi($this->getNacos());
    }

    public function testInstanceList()
    {
        $serviceName = $this->getTestsConfig('exist_service_name');

        $list = $this->api->instanceList(new ServiceParams($serviceName));
        $this->assertInstanceOf(ServiceInstanceListModel::class, $list);
        $this->assertEquals($serviceName, $list->dom);
        $this->assertIsArray($list->hosts);
        if (count($list->hosts) > 0) {
            $host = $list->hosts[0];
            $this->assertInstanceOf(ServiceHostModel::class, $host);
            $this->assertIsInt($host->port);
        }
    }

    public function testDelete()
    {
        $serviceName = $this->getTestsConfig('create_service_name');

        $data = $this->api->delete(new ServiceParams($serviceName));
        $this->assertEquals(false, $data);
    }

    public function testCreate()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        $existNsId = $this->getTestsConfig('exist_namespaces_id');

        // 简单创建
        $data = $this->api->create(new ServiceParams($serviceName));
        $this->assertEquals(true, $data);
        // 同名创建报错
        $data = $this->api->create(new ServiceParams($serviceName));
        $this->assertEquals(false, $data);
        // 删除
        $this->api->delete(new ServiceParams($serviceName));

        // 创建带参数
        $serviceParams = (new ServiceParams($serviceName))
            ->setGroupName('new_group')
            ->setMetadata(['metadata' => 11])
            ->setNamespaceId($existNsId);
        $data = $this->api->create($serviceParams);
        $this->assertEquals(true, $data);
        // 检查参数值设置是否正确
        $detail = $this->api->detail($serviceParams);
        $this->assertEquals($serviceName, $detail->name);
        $this->assertEquals('new_group', $detail->groupName);
        $this->assertEquals(['metadata' => 11], $detail->metadata);
        $this->assertEquals($existNsId, $detail->namespaceId);
        // 删除
        $this->api->delete($serviceParams);
    }

    public function testModify()
    {
        $serviceName = $this->getTestsConfig('create_service_name');
        $existNsId = $this->getTestsConfig('exist_namespaces_id');

        // 创建
        $this->api->create(new ServiceParams($serviceName));
        // 修改
        // 不能修改命名空间
        $newServiceParams = (new ServiceParams($serviceName))
            ->setProtectThreshold(0) // 该参数必须
            ->setNamespaceId($existNsId);
        $data = $this->api->modify($newServiceParams);
        $this->assertEquals(false, $data);
        // 修改meta
        $newServiceParams = (new ServiceParams($serviceName))
            ->setProtectThreshold(0) // 该参数必须
            ->setMetadata(['meta' => 111]);
        $data = $this->api->modify($newServiceParams);
        $this->assertEquals(true, $data);
        // 检查参数值设置是否正确
        $detail = $this->api->detail($newServiceParams);
        $this->assertEquals($serviceName, $detail->name);
        $this->assertEquals(['meta' => 111], $detail->metadata);
        // 删除
        $this->api->delete(new ServiceParams($serviceName));
    }

    public function testDetail()
    {
        $serviceName = $this->getTestsConfig('exist_service_name');

        $detail = $this->api->detail(new ServiceParams($serviceName));
        $this->assertEquals($serviceName, $detail->name);
        $this->assertEquals('DEFAULT_GROUP', $detail->groupName);
        $this->assertEquals([], $detail->metadata);
        $this->assertEquals('public', $detail->namespaceId);
    }

    public function testList()
    {
        $data = $this->api->list((new PageParams())->setPageNo(1)->setPageSize(3));
        $this->assertInstanceOf(ServiceListModel::class, $data);
        $this->assertIsInt($data->count);
        $this->assertIsArray($data->doms);
    }
}
