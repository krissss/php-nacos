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
        $isFirst = false;
        $lowestNodes = [];
        foreach ($nodes as $key => $weight) {
            if (!$isFirst) {
                $firstWeight = $weight;
                $lowestNodes[$key] = $weight;
                continue;
            }
            if ($firstWeight == $weight) {
                $lowestNodes[$key] = $weight;
                continue;
            }
            break;
        }
        var_dump($lowestNodes, array_rand($lowestNodes));
        return array_rand($lowestNodes);
    }
}