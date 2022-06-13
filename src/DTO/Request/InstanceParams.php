<?php

namespace Kriss\Nacos\DTO\Request;

use Kriss\Nacos\Model\InstanceModel;
use Kriss\Nacos\Support\Json;

class InstanceParams
{
    /**
     * 服务实例IP
     * @var string
     */
    private string $ip;
    /**
     * 服务实例port
     * @var int
     */
    private int $port;
    /**
     * 服务名
     * @var string
     */
    private string $serviceName;
    /**
     * 命名空间ID
     * @var string|null
     */
    private ?string $namespaceId = null;
    /**
     * 权重
     * @var double|null
     */
    private ?float $weight = null;
    /**
     * 是否上线
     * @var boolean|null
     */
    private ?bool $enabled = null;
    /**
     * 是否健康
     * @var boolean|null
     */
    private ?bool $healthy = null;
    /**
     * 扩展信息
     * @var array|null
     */
    private ?array $metadata = null;
    /**
     * 集群名
     * @var string|null
     */
    private ?string $clusterName = null;
    /**
     * 分组名
     * @var string|null
     */
    private ?string $groupName = null;
    /**
     * 是否临时实例
     * @var boolean|null
     */
    private ?bool $ephemeral = null;

    public function __construct(string $ip, int $port, string $serviceName)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->serviceName = $serviceName;
    }

    public static function loadFromInstanceModel(InstanceModel $instance): self
    {
        return (new static($instance->ip, $instance->port, $instance->serviceName))
            ->setServiceName($instance->serviceName)
            ->setNamespaceId($instance->namespaceId)
            ->setWeight($instance->weight)
            ->setEnabled($instance->enabled)
            ->setHealthy($instance->healthy)
            ->setMetadata($instance->metadata)
            ->setClusterName($instance->clusterName)
            ->setGroupName($instance->groupName)
            ->setEphemeral($instance->ephemeral);
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
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

    public function getNamespaceId(): ?string
    {
        return $this->namespaceId;
    }

    public function setNamespaceId(?string $namespaceId): self
    {
        $this->namespaceId = $namespaceId;
        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getHealthy(): ?bool
    {
        return $this->healthy;
    }

    public function setHealthy(?bool $healthy): self
    {
        $this->healthy = $healthy;
        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function getMetadataJson(): ?string
    {
        if ($this->metadata) {
            return Json::encode($this->metadata);
        }
        return null;
    }

    public function setMetadata(?array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getClusterName(): ?string
    {
        return $this->clusterName;
    }

    public function setClusterName(?string $clusterName): self
    {
        $this->clusterName = $clusterName;
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

    public function getEphemeral(): ?bool
    {
        return $this->ephemeral;
    }

    public function setEphemeral(?bool $ephemeral): self
    {
        $this->ephemeral = $ephemeral;
        return $this;
    }
}
