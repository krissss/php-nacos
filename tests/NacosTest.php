<?php

namespace Kriss\Nacos\Tests;

use Kriss\Nacos\Nacos;
use PHPUnit\Framework\TestCase;

class NacosTest extends TestCase
{
    public function test__construct()
    {
        $nacos = new Nacos(['config1' => 111, 'config2' => ['config3' => 444]]);
        $this->assertEquals(444, $nacos->config->get('config2.config3'));
    }
}
