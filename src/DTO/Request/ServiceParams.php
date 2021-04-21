<?php

namespace Kriss\Nacos\DTO\Request;

class ServiceParams
{
    /**
     * 服务名
     * @var string
     */
    private $serviceName;
    /**
     * 分组名
     * @var string|null
     */
    private $groupName;
    /**
     * 命名空间ID
     * @var string|null
     */
    private $namespaceId;
    /**
     * 保护阈值,取值0到1,默认0
     * @var float|null
     */
    private $protectThreshold;
    /**
     * 元数据
     * @var string|null
     */
    private $metadata;
    /**
     * 访问策略
     * @var string|null json 格式
     */
    private $selector;

    public function __construct(string $serviceName)
    {
        $this->serviceName = $serviceName;
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
     * @return ServiceParams
     */
    public function setServiceName(string $serviceName): ServiceParams
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
     * @return ServiceParams
     */
    public function setGroupName(?string $groupName): ServiceParams
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
     * @return ServiceParams
     */
    public function setNamespaceId(?string $namespaceId): ServiceParams
    {
        $this->namespaceId = $namespaceId;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getProtectThreshold(): ?float
    {
        return $this->protectThreshold;
    }

    /**
     * @param float|null $protectThreshold
     * @return ServiceParams
     */
    public function setProtectThreshold(?float $protectThreshold): ServiceParams
    {
        $this->protectThreshold = $protectThreshold;
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
     * @return ServiceParams
     */
    public function setMetadata(?string $metadata): ServiceParams
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSelector(): ?string
    {
        return $this->selector;
    }

    /**
     * @param string|null $selector
     * @return ServiceParams
     */
    public function setSelector(?string $selector): ServiceParams
    {
        $this->selector = $selector;
        return $this;
    }
}
