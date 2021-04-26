<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\PageParams;
use Kriss\Nacos\DTO\Request\ServiceParams;
use Kriss\Nacos\DTO\Response\ServiceDetailModel;
use Kriss\Nacos\DTO\Response\ServiceInstanceListModel;
use Kriss\Nacos\DTO\Response\ServiceListModel;
use Kriss\Nacos\Enums\NacosResponseCode;
use Kriss\Nacos\Exceptions\NacosException;

/**
 * 服务
 */
class ServiceApi extends BaseApi
{
    /**
     * 创建服务
     * @param ServiceParams $params
     * @return bool
     * @throws NacosException
     */
    public function create(ServiceParams $params): bool
    {
        try {
            $result = $this->api('/nacos/v1/ns/service', [
                'query' => [
                    'serviceName' => $params->getServiceName(),
                    'groupName' => $params->getGroupName(),
                    'namespaceId' => $params->getNamespaceId(),
                    'protectThreshold' => $params->getProtectThreshold(),
                    'metadata' => $params->getMetadataJson(),
                    'selector' => $params->getSelectorJson(),
                ],
            ], 'POST');
        } catch (NacosException $e) {
            if ($e->getCode() === NacosResponseCode::BAD_REQUEST && strpos($e->getMessage(), 'caused: specified service already exists, serviceName') === 0) {
                // 同名创建时返回失败
                return false;
            }
            throw $e;
        }
        return $result === 'ok';
    }

    /**
     * 删除服务
     * @param ServiceParams $params
     * @return bool
     * @throws NacosException
     */
    public function delete(ServiceParams $params): bool
    {
        try {
            $result = $this->api('/nacos/v1/ns/service', [
                'query' => [
                    'serviceName' => $params->getServiceName(),
                    'groupName' => $params->getGroupName(),
                    'namespaceId' => $params->getNamespaceId(),
                ],
            ], 'DELETE');
        } catch (NacosException $e) {
            if ($e->getCode() === NacosResponseCode::BAD_REQUEST && strpos($e->getMessage(), 'caused: specified service not exist, serviceName') === 0) {
                // 服务不存在时删除失败
                return false;
            }
            throw $e;
        }
        return $result === 'ok';
    }

    /**
     * 修改服务
     * @param ServiceParams $params
     * @return bool
     * @throws NacosException
     */
    public function modify(ServiceParams $params): bool
    {
        try {
            $result = $this->api('/nacos/v1/ns/service', [
                'query' => [
                    'serviceName' => $params->getServiceName(),
                    'groupName' => $params->getGroupName() ?? 'DEFAULT_GROUP',
                    'namespaceId' => $params->getNamespaceId(),
                    'protectThreshold' => $params->getProtectThreshold(), // 必须
                    'metadata' => $params->getMetadataJson(),
                    'selector' => $params->getSelectorJson(),
                ],
            ], 'PUT');
        } catch (NacosException $e) {
            if ($e->getCode() === NacosResponseCode::SERVER_ERROR && strpos($e->getMessage(), 'not found!;') !== false) {
                // caused: service DEFAULT_GROUP@@test_service_name_123123123 not found!;
                return false;
            }
            throw $e;
        }
        return $result === 'ok';
    }

    /**
     * 查询服务列表
     * @param PageParams $page
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return ServiceListModel
     * @throws NacosException
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
     * @throws NacosException
     */
    public function detail(ServiceParams $params): ?ServiceDetailModel
    {
        try {
            $result = $this->api('/nacos/v1/ns/service', [
                'query' => [
                    'serviceName' => $params->getServiceName(),
                    'groupName' => $params->getGroupName(),
                    'namespaceId' => $params->getNamespaceId(),
                ],
            ]);
        } catch (NacosException $e) {
            if ($e->getCode() === NacosResponseCode::SERVER_ERROR && strpos($e->getMessage(), 'is not found!') !== false) {
                // caused: service DEFAULT_GROUP@@php_service is not found!
                return null;
            }
            throw $e;
        }
        return new ServiceDetailModel($result);
    }

    /**
     * 查询服务下的所有实例，查询实例列表
     * @param ServiceParams $params
     * @param array $clusters
     * @param bool $healthyOnly
     * @return ServiceInstanceListModel
     * @throws NacosException
     */
    public function instanceList(ServiceParams $params, array $clusters = [], bool $healthyOnly = false): ServiceInstanceListModel
    {
        $result = $this->api('/nacos/v1/ns/instance/list', [
            'query' => [
                'serviceName' => $params->getServiceName(),
                'groupName' => $params->getGroupName(),
                'namespaceId' => $params->getNamespaceId(),
                'clusters' => implode(',', $clusters),
                'healthyOnly' => $healthyOnly,
            ],
        ]);
        return new ServiceInstanceListModel($result);
    }
}
