<?php

namespace Kriss\Nacos\DTO\Response\Service;

use Kriss\Nacos\DTO\Response\BaseModel;

/**
 * @property-read string $ip
 * @property-read int $port
 * @property-read bool $valid
 * @property-read bool $healthy
 * @property-read bool $marked
 * @property-read string $instanceId
 * @property-read array $metadata
 * @property-read bool $enabled
 * @property-read double $weight
 * @property-read string $clusterName
 * @property-read string $serviceName
 * @property-read bool $ephemeral
 */
class ServiceHostModel extends BaseModel
{

}