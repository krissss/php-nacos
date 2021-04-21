<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\PageParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\ServiceDetailModel;
use Kriss\Nacos\DTO\Response\ServiceInstanceListModel;
use Kriss\Nacos\DTO\Response\ServiceListModel;

/**
 * 服务
 */
class ServiceApi extends BaseApi
{
    /**
     * 创建服务
     * @param ServiceParams $params
     * @return bool
     */
    public function create(ServiceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/service', [
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'namespaceId' => $params->getNamespaceId(),
                'protectThreshold' => $params->getProtectThreshold(),
                'metadata' => $params->getMetadata(),
                'selector' => $params->getSelector(),
            ]),
        ], 'POST');
        return $result === 'ok';
    }

    /**
     * 删除服务
     * @param ServiceParams $params
     * @return bool
     */
    public function delete(ServiceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/service', [
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'namespaceId' => $params->getNamespaceId(),
            ]),
        ], 'DELETE');
        return $result === 'ok';
    }

    /**
     * 修改服务
     * @param ServiceParams $params
     * @return bool
     */
    public function modify(ServiceParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/service', [
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'namespaceId' => $params->getNamespaceId(),
                'protectThreshold' => $params->getProtectThreshold(),
                'metadata' => $params->getMetadata(),
                'selector' => $params->getSelector(),
            ]),
        ], 'PUT');
        return $result === 'ok';
    }

    /**
     * 查询服务列表
     * @param PageParams $page
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return ServiceListModel
     */
    public function list(PageParams $page, string $groupName = null, string $namespaceId = null): ServiceListModel
    {
        $result = $this->api('/nacos/v1/ns/service/list', [
            'query' => [
                'pageNo' => $page->getPageNo(),
                'pageSize' => $page->getPageSize(),
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ],
        ]);
        return new ServiceListModel($result);
    }

    /**
     * 查询一个服务
     * @param ServiceParams $params
     * @return ServiceDetailModel
     */
    public function detail(ServiceParams $params): ServiceDetailModel
    {
        $result = $this->api('/nacos/v1/ns/service', [
            'query' => [
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'namespaceId' => $params->getNamespaceId(),
            ],
        ]);
        return new ServiceDetailModel($result);
    }

    /**
     * 查询服务下的所有实例，查询实例列表
     * @param ServiceParams $params
     * @param array $clusters
     * @param bool $healthyOnly
     * @return ServiceInstanceListModel
     */
    public function instanceList(ServiceParams $params, array $clusters = [], bool $healthyOnly = false): ServiceInstanceListModel
    {
        $result = $this->api('/nacos/v1/ns/instance/list', [
            'query' => array_filter([
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'namespaceId' => $params->getNamespaceId(),
                'clusters' => implode(',', $clusters),
                'healthyOnly' => $healthyOnly,
            ]),
        ]);
        return new ServiceInstanceListModel($result);
    }
}
