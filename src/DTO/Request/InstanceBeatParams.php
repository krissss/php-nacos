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
     * @var boolean|null
     */
    private $ephemeral;
    /**
     * @var string json
     */
    private $beat;

    public function __construct(string $serviceName, string $beat)
    {
        $this->serviceName = $serviceName;
        $this->beat = $beat;
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
     * @return string
     */
    public function getBeat(): string
    {
        return $this->beat;
    }

    /**
     * @param string $beat
     * @return InstanceBeatParams
     */
    public function setBeat(string $beat): InstanceBeatParams
    {
        $this->beat = $beat;
        return $this;
    }
}