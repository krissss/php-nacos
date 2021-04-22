<?php

return [
    'baseUri' => 'http://127.0.0.1:8448',
    'auth_enabled' => true,
    'auth_username' => 'php_test',
    'auth_password' => '123456',
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
    ],
];
