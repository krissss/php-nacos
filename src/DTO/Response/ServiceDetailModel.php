<?php

namespace Kriss\Nacos\DTO\Response;

use Kriss\Nacos\DTO\Response\Service\ServiceClusterModel;
use Kriss\Nacos\DTO\Response\Service\ServiceSelectorModel;

/**
 * @property-read array $metadata
 * @property-read string $groupName
 * @property-read string $namespaceId
 * @property-read string $name
 * @property-read ServiceSelectorModel $selector
 * @property-read float $protectThreshold
 * @property-read array|ServiceClusterModel[] $clusters
 */
class ServiceDetailModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    protected function specialTypes(): array
    {
        return [
            'selector' => ServiceClusterModel::class,
            'clusters' => function ($value) {
                $data = [];
                foreach ($value as $item) {
                    $data[] = new ServiceClusterModel($item);
                }
                return $data;
            }
        ];
    }
}