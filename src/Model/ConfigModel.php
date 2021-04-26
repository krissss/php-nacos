<?php

namespace Kriss\Nacos\Model;

class ConfigModel extends BaseModel
{
    /**
     * @var string
     */
    public $tenant;

    /**
     * @var string
     */
    public $dataId;

    /**
     * @var string
     */
    public $group = 'DEFAULT_GROUP';

    /**
     * @var array
     */
    public $content;

    /**
     * @var string
     */
    public $type = 'json';
}