<?php

namespace Kriss\Nacos\DTO\Request;

use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\Support\Json;

class InstanceBeatJson
{
    /**
     * @var string
     */
    private $ip;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $serviceName;
    /**
     * @var string|null
     */
    private $cluster;
    /**
     * @var int|null
     */
    private $weight;
    /**
     * @var bool|null
     */
    private $scheduled;
    /**
     * @var array|null
     */
    private $metadata;

    public function __construct(string $ip, string $port, string $serviceName)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->serviceName = $serviceName;
    }

    /**
     * @param InstanceDetailModel $detailModel
     * @return static
     */
    public static function fromInstanceDetailModel(InstanceDetailModel $detailModel): self
    {
        $self = new static($detailModel->ip, $detailModel->port, $detailModel->service);
        $self->setMetadata($detailModel->metadata)
            ->setCluster($detailModel->clusterName)
            ->setWeight($detailModel->weight);

        return $self;
    }

    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return InstanceBeatJson
     */
    public function setIp(string $ip): InstanceBeatJson
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return InstanceBeatJson
     */
    public function setPort($port)
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
     * @return InstanceBeatJson
     */
    public function setServiceName(string $serviceName): InstanceBeatJson
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCluster(): ?string
    {
        return $this->cluster;
    }

    /**
     * @param string|null $cluster
     * @return InstanceBeatJson
     */
    public function setCluster(?string $cluster): InstanceBeatJson
    {
        $this->cluster = $cluster;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param int|null $weight
     * @return InstanceBeatJson
     */
    public function setWeight(?int $weight): InstanceBeatJson
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getScheduled(): ?bool
    {
        return $this->scheduled;
    }

    /**
     * @param bool|null $scheduled
     * @return InstanceBeatJson
     */
    public function setScheduled(?bool $scheduled): InstanceBeatJson
    {
        $this->scheduled = $scheduled;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @return string|null
     */
    public function getMetadataJson(): ?string
    {
        if ($this->metadata) {
            return Json::encode($this->metadata);
        }
        return null;
    }

    /**
     * @param array|null $metadata
     * @return InstanceBeatJson
     */
    public function setMetadata(?array $metadata): InstanceBeatJson
    {
        $this->metadata = $metadata;
        return $this;
    }
}