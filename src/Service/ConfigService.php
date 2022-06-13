<?php

namespace Kriss\Nacos\Service;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;
use Kriss\Nacos\DTO\Request\ConfigParams;
use Kriss\Nacos\Model\ConfigModel;
use Kriss\Nacos\NacosContainer;
use Kriss\Nacos\OpenApi\ConfigApi;
use Kriss\Nacos\Support\ConfigParser;

class ConfigService
{
    protected NacosContainer $container;
    protected ConfigApi $configApi;
    protected ConfigRepositoryInterface $config;

    public function __construct(NacosContainer $container)
    {
        $this->container = $container;
        $this->configApi = $container->get(ConfigApi::class);
        $this->config = $container->get(ConfigRepositoryInterface::class);
    }

    /**
     * 获取配置
     * @param ConfigModel $model
     * @return array|null
     */
    public function get(ConfigModel $model): ?array
    {
        if ($content = $this->configApi->get(ConfigParams::loadFromConfigModel($model))) {
            return ConfigParser::parse($content, $model->type);
        }
        return null;
    }

    /**
     * 监听配置变化并填充到 config 中
     */
    public function listenConfigs()
    {
        foreach ($this->config->get('nacos.config.listeners', []) as $configKey => $listener) {
            $model = (new ConfigModel())->load($listener);
            $model->content = $this->config->get($configKey, []);
            if ($content = $this->get($model)) {
                // nacos 中存在配置
                if ($content !== $model->content) {
                    // 配置与本地不符
                    $model->content = array_merge_recursive($model->content, $content);
                    $this->config->set($configKey, $model->content);
                }
            }
        }
    }
}