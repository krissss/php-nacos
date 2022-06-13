<?php

namespace Kriss\Nacos\DTO\Request;

class NamespaceParams
{
    /**
     * 命名空间ID
     * @var string
     */
    private string $customNamespaceId;
    /**
     * 命名空间名
     * @var string
     */
    private string $namespaceName;
    /**
     * 命名空间描述
     * @var string|null
     */
    private ?string $namespaceDesc = null;

    public function __construct(string $customNamespaceId, string $namespaceName)
    {
        $this->customNamespaceId = $customNamespaceId;
        $this->namespaceName = $namespaceName;
    }

    public function getCustomNamespaceId(): string
    {
        return $this->customNamespaceId;
    }

    public function setCustomNamespaceId(string $customNamespaceId): self
    {
        $this->customNamespaceId = $customNamespaceId;
        return $this;
    }

    public function getNamespaceName(): string
    {
        return $this->namespaceName;
    }

    public function setNamespaceName(string $namespaceName): self
    {
        $this->namespaceName = $namespaceName;
        return $this;
    }

    public function getNamespaceDesc(): ?string
    {
        return $this->namespaceDesc;
    }

    public function setNamespaceDesc(?string $namespaceDesc): self
    {
        $this->namespaceDesc = $namespaceDesc;
        return $this;
    }
}
