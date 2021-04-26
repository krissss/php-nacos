<?php

namespace Kriss\Nacos\Model;

class ServiceModel extends BaseModel
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
    public $namespaceId;

    /**
     * Between 0 to 1.
     * @var float
     */
    public $protectThreshold = 0.0;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var array
     */
    public $selector;
}