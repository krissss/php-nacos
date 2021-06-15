<?php

namespace Kriss\Nacos\Contract;

interface LoadBalancerInterface
{
    /**
     * 设置节点
     * @param array $loadNodes [$key => $weight]
     * @return $this
     */
    public function setNodes(array $loadNodes);

    /**
     * 选择一个
     * @return string|null
     */
    public function select(): ?string;
}