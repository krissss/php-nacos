<?php

namespace Kriss\Nacos\Support;

use InvalidArgumentException;
use Kriss\Nacos\Contract\LoadBalancerInterface;
use Kriss\Nacos\Contract\LoadBalancerManagerInterface;
use Kriss\Nacos\Support\LoadBalancer\Random;
use Kriss\Nacos\Support\LoadBalancer\WeightedRandom;

class LoadBalancerManager implements LoadBalancerManagerInterface
{
    protected array $strategies = [
        'random' => Random::class,
        'weighted-random' => WeightedRandom::class,
    ];

    /**
     * @inheritDoc
     */
    public function getByName(string $strategy): LoadBalancerInterface
    {
        if (!isset($this->strategies[$strategy])) {
            throw new InvalidArgumentException("strategy of name {$strategy} not support");
        }
        $class = $this->strategies[$strategy];
        return new $class();
    }
}