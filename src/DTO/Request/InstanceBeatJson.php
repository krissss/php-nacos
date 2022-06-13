<?php

namespace Kriss\Nacos\DTO\Request;

use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\Support\Json;

class InstanceBeatJson
{
    private string $ip;
    private int $port;
    private string $serviceName;
    private ?string $cluster = null;
    private ?int $weight = null;
    private ?bool $scheduled = null;
    private ?array $metadata = null;

    public function __construct(string $ip, int $port, string $serviceName)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->serviceName = $serviceName;
    }

    public static function fromInstanceDetailModel(InstanceDetailModel $detailModel): self
    {
        $self = new static($detailModel->ip, $detailModel->port, $detailModel->service);
        $self->setMetadata($detailModel->metadata)
            ->setCluster($detailModel->clusterName)
            ->setWeight($detailModel->weight);

        return $self;
    }

    public function toJson(): string
    {
        return Json::encode([
            'ip' => $this->getIp(),
            'port' => $this->getPort(),
            'serviceName' => $this->getServiceName(),
            'scheduled' => $this->getScheduled(),
            'weight' => $this->getWeight(),
            'cluster' => $this->getCluster(),
            'metadata' => $this->getMetadataJson(),
        ]);
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

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function getCluster(): ?string
    {
        return $this->cluster;
    }

    public function setCluster(?string $cluster): self
    {
        $this->cluster = $cluster;
        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getScheduled(): ?bool
    {
        return $this->scheduled;
    }

    public function setScheduled(?bool $scheduled): self
    {
        $this->scheduled = $scheduled;
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
}