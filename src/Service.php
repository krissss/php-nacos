<?php

namespace Kriss\Nacos;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\Model\ServiceModel;

class Service extends ServiceModel
{
    public function __construct(ConfigRepositoryInterface $config)
    {
        $this->load($config->get('nacos.service', []));
    }
}
