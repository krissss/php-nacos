<?php

namespace Kriss\Nacos\DTO\Request;

class InstanceBeatParams
{
    /**
     * @var string
     */
    private $serviceName;
    /**
     * @var string|null
     */
    private $groupName;
    /**
     * @var string|null
     */
    private $namespaceId;
    /**
     * @var boolean|null
     */
    private $ephemeral;
    /**
     * @var InstanceBeatJson
     */
    private $beat;

    public function __construct(string $serviceName, InstanceBeatJson $beat)
    {
        $this->serviceName = $serviceName;
        $this->beat = $beat;
    }

    /**
     * @param InstanceParams $instance
     * @return static
     */
    public static function loadFromInstanceParams(InstanceParams $instance): self
    {
        $beatJson = new InstanceBeatJson($instance->getIp(), $instance->getPort(), $instance->getServiceName());
        $beatJson->setWeight($instance->getWeight());
        return (new static($instance->getServiceName(), $beatJson))
            ->setNamespaceId($instance->getNamespaceId())
            ->setGroupName($instance->getGroupName())
            ->setEphemeral($instance->getEphemeral());
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     * @return InstanceBeatParams
     */
    public function setServiceName(string $serviceName): InstanceBeatParams
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    /**
     * @param string|null $groupName
     * @return InstanceBeatParams
     */
    public function setGroupName(?string $groupName): InstanceBeatParams
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespaceId(): ?string
    {
        return $this->namespaceId;
    }

    /**
     * @param string|null $namespaceId
     * @return InstanceBeatParams
     */
    public function setNamespaceId(?string $namespaceId): InstanceBeatParams
    {
        $this->namespaceId = $namespaceId;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEphemeral(): ?bool
    {
        return $this->ephemeral;
    }

    /**
     * @param bool|null $ephemeral
     * @return InstanceBeatParams
     */
    public function setEphemeral(?bool $ephemeral): InstanceBeatParams
    {
        $this->ephemeral = $ephemeral;
        return $this;
    }

    /**
     * @return InstanceBeatJson
     */
    public function getBeat(): InstanceBeatJson
    {
        return $this->beat;
    }

    /**
     * @return string
     */
    public function getBeatJson(): string
    {
        return $this->beat->toJson();
    }

    /**
     * @param InstanceBeatJson $beat
     * @return InstanceBeatParams
     */
    public function setBeat(InstanceBeatJson $beat): InstanceBeatParams
    {
        $this->beat = $beat;
        return $this;
    }
}