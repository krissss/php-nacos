<?php

namespace Kriss\Nacos\DTO\Request;

use Kriss\Nacos\Model\ConfigModel;

class ConfigParams
{
    private string $dataId;
    private string $group;
    /**
     * 租户信息，对应 Naocs 的命名空间ID字段
     * @var string|null
     */
    private ?string $tenant = null;

    public function __construct(string $dataId, string $group = 'DEFAULT_GROUP')
    {
        $this->dataId = $dataId;
        $this->group = $group;
    }

    public static function loadFromConfigModel(ConfigModel $config): self
    {
        return (new static($config->dataId, $config->group))
            ->setTenant($config->tenant);
    }

    public function getDataId(): string
    {
        return $this->dataId;
    }

    public function setDataId(string $dataId): self
    {
        $this->dataId = $dataId;
        return $this;
    }

    public function getGroup(): string
    {
        return $this->group;
    }

    public function setGroup(string $group): self
    {
        $this->group = $group;
        return $this;
    }

    public function getTenant(): ?string
    {
        return $this->tenant;
    }

    public function setTenant(?string $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }
}
