<?php

namespace App\Console\Commands\Nacos;

use Illuminate\Console\Command;
use Kriss\Nacos\Service\InstanceService;

class InstanceCommand extends Command
{
    protected $signature = 'nacos:instance {action : 操作: up 或 down}';
    protected $description = 'nacos 服务实例操作';
    protected $help = '';

    public function handle()
    {
        $action = $this->argument('action');

        $instanceService = app('nacos')->get(InstanceService::class);
        if ($action === 'up') {
            $isOk = $instanceService->registerAndBeat();
        } elseif ($action === 'down') {
            $isOk = $instanceService->deregister();
        } else {
            throw new \InvalidArgumentException('action 只支持 up/down');
        }

        if ($isOk) {
            $this->info($action . ' OK');
        } else {
            $this->error($action . 'ERROR');
        }

        return 0;
    }
}
