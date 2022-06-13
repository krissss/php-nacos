<?php

namespace Kriss\Nacos\Model;

class InstanceModel extends BaseModel
{
    public string $serviceName;
    public string $groupName = 'DEFAULT_GROUP';
    public string $ip;
    public int $port;
    public ?string $clusterName = null;
    public ?string $namespaceId = null;
    public ?float $weight = null;
    public ?array $metadata = null;
    public ?bool $enabled = null;
    public ?bool $ephemeral = null;
    public ?bool $healthy = null;
}