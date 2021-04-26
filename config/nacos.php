<?php

return [
    'api' => [
        'baseUri' => 'http://127.0.0.1:8848',
        'authEnable' => false,
        'authUsername' => '',
        'authPassword' => '',
    ],
    'service' => [
        // see Kriss\Nacos\Model\ServiceModel
        'serviceName' => 'php_service',
    ],
    'instance' => [
        // see Kriss\Nacos\Model\InstanceModel
        'serviceName' => 'php_service',
        'ip' => '127.0.0.1',
        'port' => 8448,
    ],
    'config' => [
        'listeners' => [
            /*'key_name' => [
                // see Kriss\Nacos\Model\ConfigModel
                'dataId' => '',
            ],*/
        ],
    ],
];
