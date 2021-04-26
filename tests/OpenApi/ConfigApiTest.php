<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Request\ConfigParams;
use Kriss\Nacos\DTO\Request\PageParams;
use Kriss\Nacos\DTO\Response\ConfigDetailModel;
use Kriss\Nacos\DTO\Response\PaginationModel;
use Kriss\Nacos\OpenApi\ConfigApi;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class ConfigApiTest extends TestCase
{
    use TestSupportTrait;

    protected $api;

    protected function setUp()
    {
        $this->api = $this->getNacos()->get(ConfigApi::class);
    }

    public function testHistoryList()
    {
        $dataId = $this->getTestConfig('create_config_data_id');

        // 发布一个配置，取最新的
        $this->api->publish(new ConfigParams($dataId), 'aaaa', 'txt');
        $data = $this->api->historyList(new ConfigParams($dataId), new PageParams());
        /** @var ConfigDetailModel[] $configs */
        /** @var PaginationModel $pagination */
        list($configs, $pagination) = $data;
        $this->assertInstanceOf(PaginationModel::class, $pagination);
        $this->assertInstanceOf(ConfigDetailModel::class, $configs[0]);
        $this->assertEquals(null, $configs[0]->content); // 取不到 content
        $this->assertIsString($configs[0]->id);
        $this->api->delete(new ConfigParams($dataId));
    }

    public function testDelete()
    {
        $dataId = $this->getTestConfig('create_config_data_id');

        $this->api->publish(new ConfigParams($dataId), 'aaaa', 'txt');
        $data = $this->api->delete(new ConfigParams($dataId));
        $this->assertEquals(true, $data);
    }

    public function testHistoryDetail()
    {
        $dataId = $this->getTestConfig('create_config_data_id');

        $data = $this->api->historyList(new ConfigParams($dataId), new PageParams());
        /** @var ConfigDetailModel[] $configs */
        list($configs,) = $data;
        $detail = $this->api->historyDetail($configs[0]->id);
        $this->assertInstanceOf(ConfigDetailModel::class, $detail);
        $this->assertIsString($detail->content);
        // 不存在时
        $detail = $this->api->historyDetail(0);
        $this->assertEquals(null, $detail);
    }

    public function __testHistoryPrevious()
    {
        // 接口问题
//        $dataId = $this->getTestConfig('create_config_data_id');
//
//        $content1 = 'test_previous1:' . time();
//        $this->api->publish(new ConfigParams($dataId), $content1, 'txt');
//        $content2 = 'test_previous2:' . time();
//        $this->api->publish(new ConfigParams($dataId), $content2, 'txt');
//        $data = $this->api->historyList(new ConfigParams($dataId), new PageParams());
//        /** @var ConfigDetailModel[] $configs */
//        list($configs,) = $data;
//        $data = $this->api->historyPrevious($configs[0]->id);
//        $this->assertInstanceOf(ConfigDetailModel::class, $data);
//        $this->assertEquals($content1, $data->content);
//
//        $this->api->delete(new ConfigParams($dataId));
    }

    public function __testListen()
    {
        // 长轮询测试不好测
    }

    public function testPublish()
    {
        $dataId = $this->getTestConfig('create_config_data_id');

        $content = 'test_publish_' . time();
        $data = $this->api->publish(new ConfigParams($dataId), $content, 'txt');
        $this->assertEquals(true, $data);
        sleep(5); // 立即发布立即查询取不到数据
        $detail = $this->api->get(new ConfigParams($dataId));
        $this->assertEquals($content, $detail);

        $this->api->delete(new ConfigParams($dataId));
    }

    public function testGet()
    {
        // 不存在配置
        $detail = $this->api->get(new ConfigParams(0));
        $this->assertEquals(null, $detail);
    }
}
