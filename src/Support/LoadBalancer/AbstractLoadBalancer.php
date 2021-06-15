<?php

namespace Kriss\Nacos\Support\LoadBalancer;

use Kriss\Nacos\Contract\LoadBalancerInterface;

abstract class AbstractLoadBalancer implements LoadBalancerInterface
{
    protected $nodes = [];

    /**
     * @inheritDoc
     */
    public function setNodes(array $loadNodes)
    {
        $this->nodes = $loadNodes;

        return $this;
    }
}