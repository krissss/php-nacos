<?php

namespace Kriss\Nacos\Contract;

interface LoadBalancerManagerInterface
{
    /**
     * 根据策略获取实现
     * @param string $strategy
     * @return LoadBalancerInterface
     */
    public function getByName(string $strategy): LoadBalancerInterface;
}