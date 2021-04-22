<?php

namespace Kriss\Nacos\DTO\Response;

/**
 * @property-read string $ip
 * @property-read int $port
 * @property-read string $state
 * @property-read string $address
 * @property-read int $failAccessCnt
 * @property-read array $extendInfo
 * 以下从 extendInfo 扩展出来
 * @property-read int $lastRefreshTime
 * @property-read string $raftPort
 * @property-read string $version
 */
class ServerModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    protected function specialTypes(): array
    {
        return [
            'extendInfo' => function ($value) {
                foreach ($value as $k => $v) {
                    $this->attributes[$k] = $v;
                }
                return $value;
            }
        ];
    }
}