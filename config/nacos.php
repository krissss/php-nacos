<?php

return [
    // API 接口参数
    'api' => [
        'baseUri' => 'http://127.0.0.1:8848',
        'authEnable' => false,
        'authUsername' => '',
        'authPassword' => '',
    ],
    // 服务
    'service' => [
        // see Kriss\Nacos\Model\ServiceModel
        'serviceName' => 'php_service',
    ],
    // 当前服务实例
    'instance' => [
        // see Kriss\Nacos\Model\InstanceModel
        'serviceName' => 'php_service',
        'ip' => '127.0.0.1',
        'port' => 8448,
    ],
    // 配置管理
    'config' => [
        // 监听
        'listeners' => [
            /*'key_name' => [
                // see Kriss\Nacos\Model\ConfigModel
                'dataId' => '',
            ],*/
        ],
    ],
];
