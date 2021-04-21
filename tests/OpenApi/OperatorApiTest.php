<?php

namespace Kriss\Nacos\Tests\OpenApi;

use Kriss\Nacos\DTO\Response\SwitchesModel;
use Kriss\Nacos\OpenApi\OperatorApi;
use Kriss\Nacos\Tests\Traits\NacosTrait;
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
    }
}
