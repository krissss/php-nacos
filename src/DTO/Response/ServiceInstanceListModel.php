<?php

namespace Kriss\Nacos\DTO\Response;

use Kriss\Nacos\DTO\Response\Service\ServiceHostModel;

/**
 * @property-read string $dom
 * @property-read int $cacheMillis
 * @property-read bool $useSpecifiedURL
 * @property-read array|ServiceHostModel[] $hosts
 * @property-read string $checksum
 * @property-read int $lastRefTime
 * @property-read string $env
 * @property-read string $clusters
 */
class ServiceInstanceListModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    protected function specialTypes(): array
    {
        return [
            'hosts' => function ($value) {
                $data = [];
                foreach ($value as $item) {
                    $data[] = new ServiceHostModel($item);
                }
                return $data;
            }
        ];
    }
}