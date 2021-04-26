<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\ConfigParams;
use Kriss\Nacos\DTO\Request\PageParams;
use Kriss\Nacos\DTO\Response\ConfigDetailModel;
use Kriss\Nacos\DTO\Response\PaginationModel;
use Kriss\Nacos\Enums\NacosResponseCode;
use Kriss\Nacos\Exceptions\NacosException;

/**
 * 配置管理
 */
class ConfigApi extends BaseApi
{
    /**
     * 获取配置
     * @param ConfigParams $params
     * @return string
     * @throws NacosException
     */
    public function get(ConfigParams $params): ?string
    {
        try {
            return $this->api('/nacos/v1/cs/configs', [
                'query' => [
                    'tenant' => $params->getTenant(),
                    'dataId' => $params->getDataId(),
                    'group' => $params->getGroup(),
                ],
                'parse_response_json' => false, // 不解析 content 返回的 json 数据
            ]);
        } catch (NacosException $e) {
            if ($e->getCode() === NacosResponseCode::NOT_FOUND) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * 监听配置
     * @param ConfigParams $params
     * @param string $contentMD5
     * @param int $longPullingTimeout 单位秒
     * @return string
     * @throws NacosException
     */
    public function listen(ConfigParams $params, string $contentMD5, $longPullingTimeout = 30): string
    {
        $listeningConfigs = "{$params->getDataId()}^2{$params->getGroup()}^2{$contentMD5}";
        if ($params->getTenant()) {
            $listeningConfigs .= "^2{$params->getTenant()}";
        }
        $listeningConfigs .= '^1';
        return $this->api('/nacos/v1/cs/configs/listener', [
            'body' => [
                'Listening-Configs' => $listeningConfigs,
            ],
            'headers' => [
                'Long-Pulling-Timeout' => $longPullingTimeout * 1000,
            ]
        ], 'POST');
    }

    /**
     * 发布配置
     * @param ConfigParams $params
     * @param string $content
     * @param string|null $type
     * @return bool
     * @throws NacosException
     */
    public function publish(ConfigParams $params, string $content, string $type = null): bool
    {
        $result = $this->api('/nacos/v1/cs/configs', [
            'body' => [
                'tenant' => $params->getTenant(),
                'dataId' => $params->getDataId(),
                'group' => $params->getGroup(),
                'content' => $content,
                'type' => $type,
            ],
        ], 'POST');
        return is_bool($result) ? $result : $result === 'true';
    }

    /**
     * 删除配置
     * @param ConfigParams $params
     * @return bool
     * @throws NacosException
     */
    public function delete(ConfigParams $params): bool
    {
        $result = $this->api('/nacos/v1/cs/configs', [
            'query' => [
                'tenant' => $params->getTenant(),
                'dataId' => $params->getDataId(),
                'group' => $params->getGroup(),
            ],
        ], 'DELETE');
        return is_bool($result) ? $result : $result === 'true';
    }

    /**
     * 查询历史版本
     * @param ConfigParams $params
     * @param PageParams $page
     * @return array [ConfigDetailModel[], PaginationModel]
     * @throws NacosException
     */
    public function historyList(ConfigParams $params, PageParams $page): array
    {
        $result = $this->api('/nacos/v1/cs/history?search=accurate', [
            'query' => [
                'tenant' => $params->getTenant(),
                'dataId' => $params->getDataId(),
                'group' => $params->getGroup(),
                'pageNo' => $page->getPageNo(),
                'pageSize' => $page->getPageSize(),
            ],
        ]);
        $list = [];
        if (isset($result['pageItems'])) {
            foreach ($result['pageItems'] as $item) {
                $list[] = new ConfigDetailModel($item);
            }
            unset($result['pageItems']);
        }
        $pagination = new PaginationModel($result);

        return [$list, $pagination];
    }

    /**
     * 查询历史版本详情
     * @param string $nid 配置项历史版本ID
     * @return ConfigDetailModel
     * @throws NacosException
     */
    public function historyDetail(string $nid): ?ConfigDetailModel
    {
        try {
            $result = $this->api('/nacos/v1/cs/history', [
                'query' => [
                    'nid' => $nid,
                ],
            ]);
        } catch (NacosException $e) {
            if ($e->getCode() === NacosResponseCode::SERVER_ERROR && strpos($e->getMessage(), 'caused: Incorrect result size: expected 1, actual 0') === 0) {
                // 不存在时
                return null;
            }
            throw $e;
        }
        return new ConfigDetailModel($result);
    }

    /**
     * 查询配置上一版本信息(1.4起)
     * @param string $id 配置ID
     * @return ConfigDetailModel
     * @throws NacosException
     * @internal 接口存在问题，未完成
     */
    public function historyPrevious(string $id): ConfigDetailModel
    {
        $result = $this->api('/nacos/v1/cs/history/previous', [
            'query' => [
                'id' => $id,
            ],
        ]);
        return new ConfigDetailModel($result);
    }
}
