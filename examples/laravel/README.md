# Laravel 例子

## 步骤

1. 复制 `config/nacos.php` 到 laravel 项目的 config 目录下（在第二步中使用），修改相关配置
   
2. 创建一个 ServiceProvider，例如: `App\Providers\NacosServiceProvider`，修改其中必须的配置

3. 将 ServiceProvider 注册到 `config/app.php`

4. 使用

    - 注册实例注册例子：`App\Console\Commands\Nacos\InstanceCommand`
    
    - 监听配置例子：`App\Console\Commands\Nacos\ConfigCommand`
