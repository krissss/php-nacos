<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Response\InstanceBeatModel;
use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\Enums\NacosResponseCode;
use Kriss\Nacos\Exceptions\NacosException;

/**
 * 服务实例
 */
class InstanceApi extends BaseApi
{
    /**
     * 注册实例
     * @param InstanceParams $params
     * @return bool
     * @throws NacosException
     */
    public function register(InstanceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/instance', [
            'query' => [
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'namespaceId' => $params->getNamespaceId(),
                'weight' => $params->getWeight(),
                'enabled' => $params->getEnabled(),
                'healthy' => $params->getHealthy(),
                'metadata' => $params->getMetadataJson(),
                'clusterName' => $params->getClusterName(),
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ephemeral' => $params->getEphemeral(),
            ],
        ], 'POST');
        return $result === 'ok';
    }

    /**
     * 注销实例
     * @param InstanceParams $params
     * @return bool
     * @throws NacosException
     */
    public function unregister(InstanceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/instance', [
            'query' => [
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'clusterName' => $params->getClusterName(),
                'namespaceId' => $params->getNamespaceId(),
                'ephemeral' => $params->getEphemeral(),
            ],
        ], 'DELETE');
        return $result === 'ok';
    }

    /**
     * 修改实例
     * @param InstanceParams $params
     * @return bool
     * @throws NacosException
     */
    public function modify(InstanceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/instance', [
            'query' => [
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'clusterName' => $params->getClusterName(),
                'namespaceId' => $params->getNamespaceId(),
                'weight' => $params->getWeight(),
                'metadata' => $params->getMetadataJson(),
                'enabled' => $params->getEnabled(),
                'ephemeral' => $params->getEphemeral(),
            ],
        ], 'PUT');
        return $result === 'ok';
    }

    /**
     * 查询实例详情
     * @param InstanceParams $params
     * @param bool $healthyOnly
     * @return InstanceDetailModel|null
     * @throws NacosException
     */
    public function detail(InstanceParams $params, bool $healthyOnly = false): ?InstanceDetailModel
    {
        try {
            $result = $this->api('/nacos/v1/ns/instance', [
                'query' => [
                    'serviceName' => $params->getServiceName(),
                    'groupName' => $params->getGroupName(),
                    'ip' => $params->getIp(),
                    'port' => $params->getPort(),
                    'namespaceId' => $params->getNamespaceId(),
                    'cluster' => $params->getClusterName(),
                    'healthyOnly' => $healthyOnly,
                    'ephemeral' => $params->getEphemeral(),
                ],
            ]);
        } catch (NacosException $e) {
            // 实例不存在
            if ($e->getCode() === NacosResponseCode::SERVER_ERROR && strpos($e->getMessage(), 'caused: no ips found for cluster') === 0) {
                return null;
            }
            // 服务不存在
            if ($e->getCode() === NacosResponseCode::SERVER_ERROR && strpos($e->getMessage(), 'caused: no service') === 0) {
                return null;
            }
            throw $e;
        }
        return new InstanceDetailModel($result);
    }

    /**
     * 发送实例心跳，当服务和实例不存在时会自动创建
     * @param InstanceBeatParams $params
     * @return InstanceBeatModel
     * @throws NacosException
     */
    public function beat(InstanceBeatParams $params): InstanceBeatModel
    {
        $result = $this->api('/nacos/v1/ns/instance/beat', [
            'query' => [
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ephemeral' => $params->getEphemeral(),
                'beat' => $params->getBeatJson(),
            ],
        ], 'PUT');
        return new InstanceBeatModel($result);
    }

    /**
     * 更新实例的健康状态
     * @param InstanceParams $params
     * @return bool
     * @throws NacosException
     */
    public function modifyHealth(InstanceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/health/instance', [
            'query' => [
                'namespaceId' => $params->getNamespaceId(),
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'clusterName' => $params->getClusterName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'healthy' => $params->getHealthy(),
            ],
        ], 'PUT');
        return $result === 'ok';
    }
}
