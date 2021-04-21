<?php

namespace Kriss\Nacos\Tests;

use Kriss\Nacos\ConfigRepository;
use PHPUnit\Framework\TestCase;

class ConfigRepositoryTest extends TestCase
{
    public function testGet()
    {
        $config = new ConfigRepository(['config1' => 111, 'config2' => ['config3' => 444]]);
        $this->assertEquals(111, $config->get('config1'));
        $this->assertEquals(444, $config->get('config2.config3'));
    }
}
