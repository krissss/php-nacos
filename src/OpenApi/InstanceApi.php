<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Response\InstanceBeatModel;
use Kriss\Nacos\DTO\Response\InstanceDetailModel;
use Kriss\Nacos\Exceptions\ServerException;

/**
 * 服务实例
 */
class InstanceApi extends BaseApi
{
    /**
     * 注册实例
     * @param InstanceParams $params
     * @return bool
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
        } catch (ServerException $e) {
            return null;
        }
        return new InstanceDetailModel($result);
    }

    /**
     * 发送实例心跳
     * @param InstanceBeatParams $params
     * @return InstanceBeatModel
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
     * @throws ServerException
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
