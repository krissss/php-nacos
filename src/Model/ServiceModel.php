<?php

namespace Kriss\Nacos\Model;

class ServiceModel extends BaseModel
{
    public string $serviceName;
    public string $groupName = 'DEFAULT_GROUP';
    public ?string $namespaceId = null;
    /**
     * Between 0 to 1.
     * @var float
     */
    public float $protectThreshold = 0.0;
    public ?array $metadata = null;
    public ?array $selector = null;
}