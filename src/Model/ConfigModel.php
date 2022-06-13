<?php

namespace Kriss\Nacos\Model;

class ConfigModel extends BaseModel
{
    public string $tenant;
    public string $dataId;
    public string $group = 'DEFAULT_GROUP';
    public array $content;
    public string $type = 'json';
}