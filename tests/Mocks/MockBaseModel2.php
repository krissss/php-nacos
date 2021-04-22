<?php

namespace Kriss\Nacos\Tests\Mocks;

use Kriss\Nacos\DTO\Response\BaseModel;

/**
 * @property-read MockBaseModel1 $base1
 * @property-read array|MockBaseModel1[] $base2
 */
class MockBaseModel2 extends BaseModel
{
    protected function specialTypes(): array
    {
        return [
            'base1' => MockBaseModel1::class,
            'base2' => function ($value) {
                $data = [];
                foreach ($value as $item) {
                    $data[] = new MockBaseModel1($item);
                }
                return $data;
            }
        ];
    }
}