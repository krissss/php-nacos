<?php

namespace Kriss\Nacos\Contract;

interface ConfigRepositoryInterface
{
    /**
     * 获取配置，支持 . 获取层级数组值
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * 保存配置
     * @param string $key
     * @param array $values
     * @return mixed
     */
    public function set(string $key, array $values);
}