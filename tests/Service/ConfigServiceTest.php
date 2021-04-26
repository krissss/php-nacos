<?php

namespace Kriss\Nacos\Tests\Service;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\DTO\Request\ConfigParams;
use Kriss\Nacos\Enums\ConfigType;
use Kriss\Nacos\Model\ConfigModel;
use Kriss\Nacos\OpenApi\ConfigApi;
use Kriss\Nacos\Service\ConfigService;
use Kriss\Nacos\Support\Json;
use Kriss\Nacos\Tests\Mocks\Traits\TestSupportTrait;
use PHPUnit\Framework\TestCase;

class ConfigServiceTest extends TestCase
{
    use TestSupportTrait;

    protected $service;
    protected $config;
    protected $configApi;

    protected $mockConfigDataModel;
    protected $mockConfigDataContent;
    protected $mockConfigKey;

    protected function setUp()
    {
        $this->service = $this->getNacos()->get(ConfigService::class);
        $this->config = $this->getNacos()->get(ConfigRepositoryInterface::class);
        $this->configApi = $this->getNacos()->get(ConfigApi::class);

        $listeners = $this->config->get('nacos.config.listeners');
        $this->mockConfigKey = array_keys($listeners)[0];
        $this->mockConfigDataModel = $listeners[$this->mockConfigKey];
        $this->mockConfigDataContent = $this->getTestConfig('exits_config_data_content');

        $this->configApi->publish(new ConfigParams($this->mockConfigDataModel['dataId']), Json::encode($this->mockConfigDataContent), ConfigType::JSON);
        sleep(3); // 刚发布的需要等会才能被 get 到
    }

    protected function tearDown()
    {
        $this->configApi->delete(new ConfigParams($this->mockConfigDataModel['dataId']));
    }


    public function testGet()
    {
        $config = $this->service->get((new ConfigModel())->load($this->mockConfigDataModel));
        $this->assertEquals($this->mockConfigDataContent, $config);
    }

    public function testListenConfigs()
    {
        $existConfigContent = $this->getTestConfig('exits_config_data_content');

        // 原 config 中不存在的
        $this->assertEquals(null, $this->config->get($this->mockConfigKey));

        $this->service->listenConfigs();

        // 从 nacos 加载过来的
        $this->assertEquals($existConfigContent, $this->config->get($this->mockConfigKey));
    }
}
