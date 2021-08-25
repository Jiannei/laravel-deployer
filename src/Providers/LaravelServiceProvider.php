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
use Jiannei\LaravelDeployer\Commands\DeployWebhook;

class LaravelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__, 2).'/config/deploy.php', 'deploy');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Deploy::class,
                DeployWebhook::class,
            ]);

            $this->publishes([dirname(__DIR__, 2).'/config/deploy.php' => config_path('deploy.php')], 'deploy');

            if ($recipesPath = $this->app['config']->get('deploy.recipes.path')) {
                set_include_path(get_include_path().PATH_SEPARATOR.$recipesPath);
            }
        }
    }
}
