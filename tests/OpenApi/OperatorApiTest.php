<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Request\SwitchParams;
use Kriss\Nacos\DTO\Response\MetricsModel;
use Kriss\Nacos\DTO\Response\ServerLeaderModel;
use Kriss\Nacos\DTO\Response\ServerModel;
use Kriss\Nacos\DTO\Response\SwitchesModel;
use Kriss\Nacos\OpenApi\OperatorApi;
use Kriss\Nacos\Tests\Mocks\Traits\NacosTrait;
use PHPUnit\Framework\TestCase;

class OperatorApiTest extends TestCase
{
    use NacosTrait;

    protected $api;

    protected function setUp()
    {
        $this->api = new OperatorApi($this->getNacos());
    }

    public function testSwitches()
    {
        $data = $this->api->switches();
        $this->assertInstanceOf(SwitchesModel::class, $data);
        $this->assertIsString($data->name);
        $this->assertIsBool($data->distroEnabled);
    }

    public function testCurrentServerLoader()
    {
        $data = $this->api->currentServerLoader();
        $this->assertInstanceOf(ServerLeaderModel::class, $data);
        $this->assertIsString($data->ip);
        $this->assertIsInt($data->heartbeatDueMs);
    }

    public function testMetrics()
    {
        $data = $this->api->metrics();
        $this->assertInstanceOf(MetricsModel::class, $data);
        $this->assertIsFloat($data->cpu);
        $this->assertIsInt($data->serviceCount);
    }

    public function testServers()
    {
        $data = $this->api->servers();
        $this->assertIsArray($data);
        if (count($data) > 0) {
            $server = $data[0];
            $this->assertInstanceOf(ServerModel::class, $server);
            $this->assertIsString($server->ip);
            $this->assertIsInt($server->port);
        }
    }

    public function testSwitchModify()
    {
        // 记录旧的
        $switch = $this->api->switches();
        $oldValue = $switch->pushEnabled;
        // 新值取反
        $newValue = !$oldValue;
        // 修改
        $data = $this->api->switchModify(new SwitchParams('pushEnabled', $newValue));
        // 检验执行结果
        $this->assertEquals(true, $data);
        // 检测新值
        $switch = $this->api->switches();
        $this->assertEquals($newValue, $switch->pushEnabled);
        // 修改回原来的值
        $this->api->switchModify(new SwitchParams('pushEnabled', $oldValue));
    }
}
