<?php

namespace Kriss\Nacos\Model;

class InstanceModel extends BaseModel
{
    /**
     * @var string
     */
    public $serviceName;

    /**
     * @var string
     */
    public $groupName = 'DEFAULT_GROUP';

    /**
     * @var string
     */
    public $ip;

    /**
     * @var int
     */
    public $port;

    /**
     * @var null|string
     */
    public $clusterName;

    /**
     * @var null|string
     */
    public $namespaceId;

    /**
     * @var null|float|int
     */
    public $weight;

    /**
     * @var null|array
     */
    public $metadata;

    /**
     * @var null|bool
     */
    public $enabled;

    /**
     * @var null|bool
     */
    public $ephemeral;

    /**
     * @var null|bool
     */
    public $healthy;
}