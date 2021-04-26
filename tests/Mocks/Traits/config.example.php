<?php

return [
    'api' => [
        'baseUri' => 'http://192.168.0.203:8848',
        'authEnable' => true,
        'authUsername' => 'php_test',
        'authPassword' => '123456',
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
            'key_name' => [
                // see Kriss\Nacos\Model\ConfigModel
                'dataId' => 'php_test_config',
            ],
        ],
    ],
    // 以下为测试数据
    'tests' => [
        'test_key' => 'test_value',
        'exist_namespaces_id' => 'dev',
        'exist_namespaces_name' => 'dev',
        'create_namespace_id' => 'test_ns_id_1283817238',
        'create_namespace_name' => 'test_ns_name',
        'exist_service_name' => 'my-auth', // 在默认 ns 下存在的一个服务
        'create_service_name' => 'test_service_name_123123123',
        'create_instance' => ['127.0.0.1', 80],
        'is_heath_check_working' => true, // 集群的健康检查是否开启
        'create_config_data_id' => 'test_config_125454878',
        'auth_username' => 'php_test',
        'auth_password' => '123456',
        'exits_config_data_content' => [ // 在 nacos 中存在的，dataId 为 上方 config.listeners 中第一个的配置的值
            'aa' => 'xx',
            'bb' => 'yy',
        ],
    ],
];
