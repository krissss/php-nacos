<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\InstanceBeatParams;
use Kriss\Nacos\DTO\Request\InstanceParams;
use Kriss\Nacos\DTO\Response\InstanceDetailModel;

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
            'query' => array_filter([
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'namespaceId' => $params->getNamespaceId(),
                'weight' => $params->getWeight(),
                'enabled' => $params->getEnabled(),
                'healthy' => $params->getHealthy(),
                'metadata' => $params->getMetadata(),
                'clusterName' => $params->getClusterName(),
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ephemeral' => $params->getEphemeral(),
            ]),
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
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'clusterName' => $params->getClusterName(),
                'namespaceId' => $params->getNamespaceId(),
                'ephemeral' => $params->getEphemeral(),
            ]),
        ], 'POST');
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
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'clusterName' => $params->getClusterName(),
                'namespaceId' => $params->getNamespaceId(),
                'weight' => $params->getWeight(),
                'metadata' => $params->getMetadata(),
                'enabled' => $params->getEnabled(),
                'ephemeral' => $params->getEphemeral(),
            ]),
        ], 'PUT');
        return $result === 'ok';
    }

    /**
     * 查询实例详情
     * @param InstanceParams $params
     * @param bool $healthyOnly
     * @return InstanceDetailModel
     */
    public function detail(InstanceParams $params, bool $healthyOnly = false): InstanceDetailModel
    {
        $result = $this->api('/nacos/v1/ns/instance', [
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'namespaceId' => $params->getNamespaceId(),
                'cluster' => $params->getClusterName(),
                'healthyOnly' => $healthyOnly,
                'ephemeral' => $params->getEphemeral(),
            ]),
        ]);
        return new InstanceDetailModel($result);
    }

    /**
     * 发送实例心跳
     * @param InstanceBeatParams $params
     * @return bool
     */
    public function beat(InstanceBeatParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/instance/beat', [
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'ephemeral' => $params->getEphemeral(),
                'beat' => $params->getBeat(),
            ]),
        ], 'PUT');
        return $result === 'ok';
    }

    public function modifyHealth(InstanceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/health/instance', [
            'query' => array_filter([
                'namespaceId' => $params->getNamespaceId(),
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'clusterName' => $params->getClusterName(),
                'ip' => $params->getIp(),
                'port' => $params->getPort(),
                'healthy' => $params->getHealthy(),
            ]),
        ], 'PUT');
        return $result === 'ok';
    }
}
