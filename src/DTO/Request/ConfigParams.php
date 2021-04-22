<?php

namespace Kriss\Nacos\DTO\Request;

class ConfigParams
{
    /**
     * 配置 ID
     * @var string
     */
    private $dataId;
    /**
     * 	配置分组
     * @var string
     */
    private $group;
    /**
     * 租户信息，对应 Naocs 的命名空间ID字段
     * @var string|null
     */
    private $tenant;

    public function __construct(string $dataId, string $group = 'DEFAULT_GROUP')
    {
        $this->dataId = $dataId;
        $this->group = $group;
    }

    /**
     * @return string
     */
    public function getDataId(): string
    {
        return $this->dataId;
    }

    /**
     * @param string $dataId
     * @return ConfigParams
     */
    public function setDataId(string $dataId): ConfigParams
    {
        $this->dataId = $dataId;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string $group
     * @return ConfigParams
     */
    public function setGroup(string $group): ConfigParams
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTenant(): ?string
    {
        return $this->tenant;
    }

    /**
     * @param string|null $tenant
     * @return ConfigParams
     */
    public function setTenant(?string $tenant): ConfigParams
    {
        $this->tenant = $tenant;
        return $this;
    }
}
