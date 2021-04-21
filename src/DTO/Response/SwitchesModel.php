<?php

namespace Kriss\Nacos\DTO\Response;

use Kriss\Nacos\DTO\Response\Swithes\SwitchHealthParamsModel;

/**
 * @property-read null|mixed $masters
 * @property-read array $adWeightMap
 * @property-read int $defaultPushCacheMillis
 * @property-read int $clientBeatInterval
 * @property-read int $defaultCacheMillis
 * @property-read float $distroThreshold
 * @property-read bool $healthCheckEnabled
 * @property-read bool $autoChangeHealthCheckEnabled
 * @property-read bool $distroEnabled
 * @property-read bool $enableStandalone
 * @property-read bool $pushEnabled
 * @property-read int $checkTimes
 * @property-read SwitchHealthParamsModel $httpHealthParams
 * @property-read SwitchHealthParamsModel $tcpHealthParams
 * @property-read SwitchHealthParamsModel $mysqlHealthParams
 * @property-read array $incrementalList
 * @property-read int $serverStatusSynchronizationPeriodMillis
 * @property-read int $serviceStatusSynchronizationPeriodMillis
 * @property-read bool $disableAddIP
 * @property-read bool $sendBeatOnly
 * @property-read bool $lightBeatEnabled
 * @property-read array $limitedUrlMap
 * @property-read int $distroServerExpiredMillis
 * @property-read string $pushGoVersion
 * @property-read string $pushJavaVersion
 * @property-read string $pushPythonVersion
 * @property-read string $pushCVersion
 * @property-read string $pushCSharpVersion
 * @property-read bool $enableAuthentication
 * @property-read null|mixed $overriddenServerStatus
 * @property-read bool $defaultInstanceEphemeral
 * @property-read array $healthCheckWhiteList
 * @property-read null|mixed $checksum
 * @property-read string $name
 */
class SwitchesModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    protected function specialTypes(): array
    {
        return [
            'httpHealthParams' => SwitchHealthParamsModel::class,
            'tcpHealthParams' => SwitchHealthParamsModel::class,
            'mysqlHealthParams' => SwitchHealthParamsModel::class,
        ];
    }
}
