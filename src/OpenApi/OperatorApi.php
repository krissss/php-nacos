<?php

namespace Kriss\Nacos\OpenApi;

use Kriss\Nacos\DTO\Request\SwitchParams;
use Kriss\Nacos\DTO\Response\MetricsModel;
use Kriss\Nacos\DTO\Response\ServerLeaderModel;
use Kriss\Nacos\DTO\Response\ServerModel;
use Kriss\Nacos\DTO\Response\SwitchesModel;

/**
 * 系统开关
 */
class OperatorApi extends BaseApi
{
    /**
     * 查询系统开关
     * @return SwitchesModel
     */
    public function switches(): SwitchesModel
    {
        $result = $this->api('/nacos/v1/ns/operator/switches');
        return new SwitchesModel($result);
    }

    /**
     * 修改系统开关
     * @param SwitchParams $params
     * @return bool
     */
    public function switchModify(SwitchParams $params): bool
    {
        $result = $this->api('/nacos/v1/ns/operator/switches', [
            'query' => [
                'entry' => $params->getEntry(),
                'value' => $params->getValue(),
                'debug' => $params->getDebug(),
            ],
        ], 'PUT');
        return $result === 'ok';
    }

    /**
     * 查看系统当前数据指标
     * @return MetricsModel
     */
    public function metrics(): MetricsModel
    {
        $result = $this->api('/nacos/v1/ns/operator/metrics');
        return new MetricsModel($result);
    }

    /**
     * 查看当前集群Server列表
     * @param bool|null $healthy 是否只返回健康Server节点
     * @return array|ServerModel[]
     */
    public function servers(bool $healthy = null): array
    {
        $result = $this->api('/nacos/v1/ns/operator/servers', [
            'query' => [
                'healthy' => $healthy,
            ]
        ]);
        $list = [];
        if (isset($result['servers'])) {
            foreach ($result['servers'] as $server) {
                $list[] = new ServerModel($server);
            }
        }
        return $list;
    }

    /**
     * 查看当前集群leader
     * @return ServerLeaderModel|null
     */
    public function currentServerLoader(): ?ServerLeaderModel
    {
        $result = $this->api('/nacos/v1/ns/raft/leader');
        if (isset($result['leader'])) {
            return new ServerLeaderModel($result['leader']);
        }
        return null;
    }
}
