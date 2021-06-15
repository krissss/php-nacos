<?php

namespace Kriss\Nacos\Support\LoadBalancer;

class Random extends AbstractLoadBalancer
{
    /**
     * @inheritDoc
     */
    public function select(): ?string
    {
        if (!$this->nodes) {
            return null;
        }
        return array_rand($this->nodes);
    }
}