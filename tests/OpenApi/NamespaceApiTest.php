<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Request\NamespaceParams;
use Kriss\Nacos\OpenApi\NamespaceApi;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class NamespaceApiTest extends TestCase
{
    use TestSupportTrait;

    protected $api;

    protected function setUp()
    {
        $this->api = $this->getNacos()->get(NamespaceApi::class);
    }

    public function testList()
    {
        $id = $this->getTestConfig('exist_namespaces_id');
        $name = $this->getTestConfig('exist_namespaces_name');

        $data = $this->api->list();
        $has = false;
        foreach ($data as $item) {
            if ($item->namespace === $id && $item->namespaceShowName === $name) {
                $has = true;
                break;
            }
        }

        $this->assertEquals(true, $has);
    }

    public function testDelete()
    {
        $nsId = $this->getTestConfig('create_namespace_id');

        $data = $this->api->delete($nsId);
        $this->assertEquals(true, $data);
    }

    public function testCreate()
    {
        $nsId = $this->getTestConfig('create_namespace_id');
        $nsName = $this->getTestConfig('create_namespace_name');

        // 新增
        $data = $this->api->create(new NamespaceParams($nsId, $nsName));
        $this->assertEquals(true, $data);
        // 检查是否有新增的
        $data = $this->api->list();
        $has = false;
        foreach ($data as $item) {
            if ($item->namespace === $nsId && $item->namespaceShowName === $nsName) {
                $has = true;
                break;
            }
        }
        $this->assertEquals(true, $has);
        // 删除新增的
        $this->api->delete($nsId);
    }

    public function testModify()
    {
        $nsId = $this->getTestConfig('create_namespace_id');
        $nsName = $this->getTestConfig('create_namespace_name');
        $nsDesc = 'desc';

        // 新增
        $this->api->create(new NamespaceParams($nsId, $nsName));
        // 修改
        $newNsName = $nsName . '_new';
        $data = $this->api->modify((new NamespaceParams($nsId, $newNsName))->setNamespaceDesc($nsDesc));
        $this->assertEquals(true, $data);
        // 检查修改后的
        $data = $this->api->list();
        foreach ($data as $item) {
            if ($item->namespace === $nsId) {
                $this->assertEquals($newNsName, $item->namespaceShowName);
                // 目前无法通过接口查看 desc 修改情况，到后台管理界面可以查看
                //$this->assertEquals($nsDesc, $item->type);
                break;
            }
        }
        // 删除
        $this->api->delete($nsId);
    }


}
