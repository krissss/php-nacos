<?php

namespace Kriss\Nacos\Tests\DTO\Response;

use Kriss\Nacos\DTO\Response\BaseModel;

/**
 * @property-read BaseModel1 $base1
 * @property-read array|BaseModel1[] $base2
 */
class BaseModel2 extends BaseModel
{
    protected function specialTypes(): array
    {
        return [
            'base1' => BaseModel1::class,
            'base2' => function ($value) {
                $data = [];
                foreach ($value as $item) {
                    $data[] = new BaseModel1($item);
                }
                return $data;
            }
        ];
    }
}