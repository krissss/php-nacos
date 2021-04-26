<?php

namespace Kriss\Nacos;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Model\InstanceModel;

class Instance extends InstanceModel
{
    public function __construct(ConfigRepositoryInterface $config)
    {
        $this->load($config->get('nacos.instance', []));
    }
}
