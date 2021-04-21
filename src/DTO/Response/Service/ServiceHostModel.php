<?php

namespace Kriss\Nacos\DTO\Response\Service;

use Kriss\Nacos\DTO\Response\BaseModel;

/**
 * @property-read bool $valid
 * @property-read bool $marked
 * @property-read string $instanceId
 * @property-read int $port
 * @property-read string $ip
 * @property-read float $weight
 * @property-read array $metadata
 */
class ServiceHostModel extends BaseModel
{

}