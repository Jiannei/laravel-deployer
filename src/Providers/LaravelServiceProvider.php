<?php


namespace Jiannei\LaravelDeployer\Providers;

use Illuminate\Support\ServiceProvider;
use Jiannei\LaravelDeployer\Commands\Deploy;
use Jiannei\LaravelDeployer\Commands\DeployWebhook;

class LaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Deploy::class,
                DeployWebhook::class,
            ]);
        }
    }
}
