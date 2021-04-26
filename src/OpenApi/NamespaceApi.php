<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\NamespaceParams;
use Kriss\Nacos\DTO\Response\NamespaceModel;
use Kriss\Nacos\Exceptions\NacosException;

/**
 * 命名空间
 */
class NamespaceApi extends BaseApi
{
    /**
     * 查询命名空间列表
     * @return array|NamespaceModel[]
     * @throws NacosException
     */
    public function list(): array
    {
        $result = $this->api('/nacos/v1/console/namespaces');
        $list = [];
        if (isset($result['data'])) {
            foreach ($result['data'] as $item) {
                $list[] = new NamespaceModel($item);
            }
        }
        return $list;
    }

    /**
     * 创建命名空间
     * @param NamespaceParams $params
     * @return bool
     * @throws NacosException
     */
    public function create(NamespaceParams $params): bool
    {
        $result = $this->api('/nacos/v1/console/namespaces', [
            'body' => [
                'customNamespaceId' => $params->getCustomNamespaceId(),
                'namespaceName' => $params->getNamespaceName(),
                'namespaceDesc' => $params->getNamespaceDesc(),
            ],
        ], 'POST');
        return is_bool($result) ? $result : $result === 'true';
    }

    /**
     * 修改命名空间
     * @param NamespaceParams $params
     * @return bool
     * @throws NacosException
     */
    public function modify(NamespaceParams $params): bool
    {
        $result = $this->api('/nacos/v1/console/namespaces', [
            'body' => [
                'namespace' => $params->getCustomNamespaceId(),
                'namespaceShowName' => $params->getNamespaceName(),
                'namespaceDesc' => $params->getNamespaceDesc(),
            ],
        ], 'PUT');
        return is_bool($result) ? $result : $result === 'true';
    }

    /**
     * 删除命名空间
     * 当不存在该命名空间ID时，返回的值也是 true
     * @param string $namespaceId 命名空间ID
     * @return bool
     * @throws NacosException
     */
    public function delete(string $namespaceId): bool
    {
        $result = $this->api('/nacos/v1/console/namespaces', [
            'body' => [
                'namespaceId' => $namespaceId,
            ],
        ], 'DELETE');
        return is_bool($result) ? $result : $result === 'true';
    }
}
