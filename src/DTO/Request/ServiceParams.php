<?php

namespace Kriss\Nacos\DTO\Request;

use Kriss\Nacos\Model\ServiceModel;
use Kriss\Nacos\Support\Json;

class ServiceParams
{
    /**
     * 服务名
     * @var string
     */
    private string $serviceName;
    /**
     * 分组名
     * @var string|null
     */
    private ?string $groupName = null;
    /**
     * 命名空间ID
     * @var string|null
     */
    private ?string $namespaceId = null;
    /**
     * 保护阈值,取值0到1,默认0
     * @var float|null
     */
    private ?float $protectThreshold = null;
    /**
     * 元数据
     * @var array|null
     */
    private ?array $metadata = null;
    /**
     * 访问策略
     * @var array|null
     */
    private ?array $selector = null;

    public function __construct(string $serviceName)
    {
        $this->serviceName = $serviceName;
    }

    public static function loadFromServiceModel(ServiceModel $service): self
    {
        return (new static($service->serviceName))
            ->setNamespaceId($service->namespaceId)
            ->setGroupName($service->groupName)
            ->setProtectThreshold($service->protectThreshold)
            ->setMetadata($service->metadata)
            ->setSelector($service->selector);
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

    public function getProtectThreshold(): ?float
    {
        return $this->protectThreshold;
    }

    public function setProtectThreshold(?float $protectThreshold): self
    {
        $this->protectThreshold = $protectThreshold;
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

    public function getSelector(): ?array
    {
        return $this->selector;
    }

    public function getSelectorJson(): ?string
    {
        if ($this->selector) {
            return Json::encode($this->selector);
        }
        return null;
    }

    public function setSelector(?array $selector): self
    {
        $this->selector = $selector;
        return $this;
    }
}
