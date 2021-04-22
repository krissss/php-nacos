<?php

namespace Kriss\Nacos\Tests\DTO\Response;

use Kriss\Nacos\Tests\Mocks\MockBaseModel1;
use Kriss\Nacos\Tests\Mocks\MockBaseModel2;
use PHPUnit\Framework\TestCase;

class BaseModelTest extends TestCase
{
    public function test__get()
    {
        $class = new MockBaseModel1(['aa' => 'bb', 'bb' => false]);
        $this->assertEquals('bb', $class->aa);
        $this->assertEquals(false, $class->bb);

        $class = new MockBaseModel2(['base1' => ['aa' => 'cc', 'bb' => false], 'base2' => [['aa' => 'dd', 'bb' => true], ['aa' => 'bb', 'bb' => false]]]);
        $this->assertEquals('cc', $class->base1->aa);
        $this->assertEquals('dd', $class->base2[0]->aa);
    }
}
