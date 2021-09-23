<?php

/*
 * This file is part of the Jiannei/laravel-deployer.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\LaravelDeployer\Providers;

use Illuminate\Support\ServiceProvider;
use Jiannei\LaravelDeployer\Commands\Deploy;
use Jiannei\LaravelDeployer\Commands\DeployIdentity;
use Jiannei\LaravelDeployer\Commands\DeployWebhook;

class LumenServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__, 2).'/config/deploy.php', 'deploy');
    }

    public function boot()
    {
        $this->app->configure('response');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Deploy::class,
                DeployIdentity::class,
                DeployWebhook::class,
            ]);

        }
    }
}
