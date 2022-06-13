<?php

namespace Kriss\Nacos;

use League\Container\Container;
use League\Container\Definition\DefinitionAggregateInterface;
use League\Container\Inflector\InflectorAggregateInterface;
use League\Container\ReflectionContainer;
use League\Container\ServiceProvider\ServiceProviderAggregateInterface;

class NacosContainer extends Container
{
    protected static ?NacosContainer $instance = null;

    final public function __construct(DefinitionAggregateInterface $definitions = null, ServiceProviderAggregateInterface $providers = null, InflectorAggregateInterface $inflectors = null)
    {
        parent::__construct($definitions, $providers, $inflectors);

        $this->delegate(
            (new ReflectionContainer())->cacheResolutions()
        );
        $this->setInstance();
    }

    final public static function getInstance(): self
    {
        if (!static::$instance) {
            $self = new static();
            $self->setInstance();
        }

        return static::$instance;
    }

    protected function setInstance()
    {
        static::$instance = $this;
        $this->add(self::class, $this);
    }
}