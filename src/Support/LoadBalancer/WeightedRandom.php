<?php

namespace Kriss\Nacos\Support\LoadBalancer;

class WeightedRandom extends AbstractLoadBalancer
{
    /**
     * @inheritDoc
     */
    public function select(): ?string
    {
        if (!$this->nodes) {
            return null;
        }
        $nodes = $this->nodes;
        asort($nodes);
        $firstWeight = 0;
        $isFirst = true;
        $lowestNodes = [];
        foreach ($nodes as $key => $weight) {
            if ($isFirst) {
                $firstWeight = $weight;
                $lowestNodes[$key] = $weight;
                $isFirst = false;
                continue;
            }
            if ($firstWeight == $weight) {
                $lowestNodes[$key] = $weight;
                continue;
            }
            break;
        }
        return array_rand($lowestNodes);
    }
}