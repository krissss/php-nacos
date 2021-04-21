<?php

namespace Kriss\Nacos\DTO\Request;

class NamespaceParams
{
    /**
     * 命名空间ID
     * @var string
     */
    private $customNamespaceId;
    /**
     * 命名空间名
     * @var string
     */
    private $namespaceName;
    /**
     * 命名空间描述
     * @var string|null
     */
    private $namespaceDesc;

    public function __construct(string $customNamespaceId, string $namespaceName)
    {
        $this->customNamespaceId = $customNamespaceId;
        $this->namespaceName = $namespaceName;
    }

    /**
     * @return string
     */
    public function getCustomNamespaceId(): string
    {
        return $this->customNamespaceId;
    }

    /**
     * @param string $customNamespaceId
     * @return NamespaceParams
     */
    public function setCustomNamespaceId(string $customNamespaceId): NamespaceParams
    {
        $this->customNamespaceId = $customNamespaceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->namespaceName;
    }

    /**
     * @param string $namespaceName
     * @return NamespaceParams
     */
    public function setNamespaceName(string $namespaceName): NamespaceParams
    {
        $this->namespaceName = $namespaceName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespaceDesc(): ?string
    {
        return $this->namespaceDesc;
    }

    /**
     * @param string|null $namespaceDesc
     * @return NamespaceParams
     */
    public function setNamespaceDesc(?string $namespaceDesc): NamespaceParams
    {
        $this->namespaceDesc = $namespaceDesc;
        return $this;
    }
}
