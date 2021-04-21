<?php

namespace Kriss\Nacos\DTO\Response\Service;

use Kriss\Nacos\DTO\Response\BaseModel;

/**
 * @property-read ServiceHealthCheckerModel $healthChecker
 * @property-read array $metadata
 * @property-read string $name
 */
class ServiceClusterModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    protected function specialTypes(): array
    {
        return [
            'healthChecker' => ServiceHealthCheckerModel::class,
        ];
    }
}