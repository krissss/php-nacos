<?php

namespace Kriss\Nacos\DTO\Request;

class InstanceParams
{
    /**
     * 服务实例IP
     * @var string
     */
    private $ip;
    /**
     * 服务实例port
     * @var int
     */
    private $port;
    /**
     * 服务名
     * @var string
     */
    private $serviceName;
    /**
     * 命名空间ID
     * @var string|null
     */
    private $namespaceId;
    /**
     * 权重
     * @var double|null
     */
    private $weight;
    /**
     * 是否上线
     * @var boolean|null
     */
    private $enabled;
    /**
     * 是否健康
     * @var boolean|null
     */
    private $healthy;
    /**
     * 扩展信息
     * @var string|null
     */
    private $metadata;
    /**
     * 集群名
     * @var string|null
     */
    private $clusterName;
    /**
     * 分组名
     * @var string|null
     */
    private $groupName;
    /**
     * 是否临时实例
     * @var boolean|null
     */
    private $ephemeral;

    public function __construct(string $ip, int $port, string $serviceName)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->serviceName = $serviceName;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return InstanceParams
     */
    public function setIp(string $ip): InstanceParams
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return InstanceParams
     */
    public function setPort(int $port): InstanceParams
    {
        $this->port = $port;
        return $this;
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
     * @return InstanceParams
     */
    public function setServiceName(string $serviceName): InstanceParams
    {
        $this->serviceName = $serviceName;
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
     * @return InstanceParams
     */
    public function setNamespaceId(?string $namespaceId): InstanceParams
    {
        $this->namespaceId = $namespaceId;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     * @return InstanceParams
     */
    public function setWeight(?float $weight): InstanceParams
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     * @return InstanceParams
     */
    public function setEnabled(?bool $enabled): InstanceParams
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getHealthy(): ?bool
    {
        return $this->healthy;
    }

    /**
     * @param bool|null $healthy
     * @return InstanceParams
     */
    public function setHealthy(?bool $healthy): InstanceParams
    {
        $this->healthy = $healthy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetadata(): ?string
    {
        return $this->metadata;
    }

    /**
     * @param string|null $metadata
     * @return InstanceParams
     */
    public function setMetadata(?string $metadata): InstanceParams
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClusterName(): ?string
    {
        return $this->clusterName;
    }

    /**
     * @param string|null $clusterName
     * @return InstanceParams
     */
    public function setClusterName(?string $clusterName): InstanceParams
    {
        $this->clusterName = $clusterName;
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
     * @return InstanceParams
     */
    public function setGroupName(?string $groupName): InstanceParams
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
     * @return InstanceParams
     */
    public function setEphemeral(?bool $ephemeral): InstanceParams
    {
        $this->ephemeral = $ephemeral;
        return $this;
    }
}
