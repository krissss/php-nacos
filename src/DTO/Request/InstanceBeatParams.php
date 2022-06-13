<?php

namespace Kriss\Nacos\DTO\Request;

class InstanceBeatParams
{
    private string $serviceName;
    private ?string $groupName = null;
    private ?string $namespaceId = null;
    private ?bool $ephemeral = null;
    private InstanceBeatJson $beat;

    public function __construct(string $serviceName, InstanceBeatJson $beat)
    {
        $this->serviceName = $serviceName;
        $this->beat = $beat;
    }

    public static function loadFromInstanceParams(InstanceParams $instance): self
    {
        $beatJson = new InstanceBeatJson($instance->getIp(), $instance->getPort(), $instance->getServiceName());
        $beatJson->setWeight($instance->getWeight());
        return (new static($instance->getServiceName(), $beatJson))
            ->setNamespaceId($instance->getNamespaceId())
            ->setGroupName($instance->getGroupName())
            ->setEphemeral($instance->getEphemeral());
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }
    
    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(?string $groupName): self
    {
        $this->groupName = $groupName;
        return $this;
    }

    public function getNamespaceId(): ?string
    {
        return $this->namespaceId;
    }

    public function setNamespaceId(?string $namespaceId): self
    {
        $this->namespaceId = $namespaceId;
        return $this;
    }

    public function getEphemeral(): ?bool
    {
        return $this->ephemeral;
    }

    public function setEphemeral(?bool $ephemeral): self
    {
        $this->ephemeral = $ephemeral;
        return $this;
    }

    public function getBeat(): InstanceBeatJson
    {
        return $this->beat;
    }

    public function getBeatJson(): string
    {
        return $this->beat->toJson();
    }

    public function setBeat(InstanceBeatJson $beat): self
    {
        $this->beat = $beat;
        return $this;
    }
}