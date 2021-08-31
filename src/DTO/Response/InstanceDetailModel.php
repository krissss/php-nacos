<?php

namespace Kriss\Nacos\DTO\Response;

/**
 * @property-read array|null $metadata
 * @property-read string $instanceId
 * @property-read int $port
 * @property-read string $service
 * @property-read string $healthy
 * @property-read string $ip
 * @property-read string $clusterName
 * @property-read float $weight
 */
class InstanceDetailModel extends BaseModel
{
    public function toJson()
    {
        $this->attributes['serviceName'] = $this->service;
        //unset($this->attributes['metadata'], $this->attributes['service']);
        return json_encode($this->attributes);
    }
}