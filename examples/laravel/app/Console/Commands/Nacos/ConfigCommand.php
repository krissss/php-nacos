<?php

namespace App\Console\Commands\Nacos;

use Kriss\Nacos\Service\ConfigService;

class ConfigCommand extends Command
{
    protected $signature = 'nacos:config {action : 操作: listen}';
    protected $description = 'nacos 配置操作';
    protected $help = '';

    public function handle()
    {
        $action = $this->argument('action');

        $configService = app('nacos')->get(ConfigService::class);
        if ($action === 'listen') {
            $isOk = $configService->listenConfigs();
        } else {
            throw new \InvalidArgumentException('action 只支持 listen');
        }

        if ($isOk) {
            $this->info($action . ' OK');
        } else {
            $this->error($action . 'ERROR');
        }

        return 0;
    }
}
