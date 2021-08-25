<?php

/*
 * This file is part of the Jiannei/laravel-deployer.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\LaravelDeployer\Commands;

use Illuminate\Console\Command;
use Jiannei\LaravelDeployer\Events\DeployHookBuildEvent;
use Jiannei\LaravelDeployer\Events\DeployHookDoneEvent;
use Jiannei\LaravelDeployer\Events\DeployHookFailEvent;
use Jiannei\LaravelDeployer\Events\DeployHookStartEvent;
use Jiannei\LaravelDeployer\Events\DeployHookSuccessEvent;
use Symfony\Component\Console\Input\InputArgument;

class DeployWebhook extends Command
{
    protected $name = 'dep:webhook';

    protected $description = 'Deploy webhooks.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        $workspace = $this->argument('workspace');
        $project = $this->argument('project');
        $release = $this->argument('release');
        $stage = $this->argument('stage');

        switch ($stage) {
            case 'start':
                event(new DeployHookStartEvent($id, $workspace, $project, $release, $stage));
                break;
            case 'build':
                event(new DeployHookBuildEvent($id, $workspace, $project, $release, $stage));
                break;
            case 'done':
                event(new DeployHookDoneEvent($id, $workspace, $project, $release, $stage));
                break;
            case 'success':
                event(new DeployHookSuccessEvent($id, $workspace, $project, $release, $stage));
                break;
            case 'fail':
                event(new DeployHookFailEvent($id, $workspace, $project, $release, $stage));
                break;
            default:
                $this->error('Unsupported hook type');

                return 1; // throw exception
        }

        return 0;
    }

    protected function getArguments(): array
    {
        return [
            ['id', InputArgument::REQUIRED, 'id', null],
            ['workspace', InputArgument::REQUIRED, 'Workspace', null],
            ['project', InputArgument::REQUIRED, 'Project', null],
            ['release', InputArgument::REQUIRED, 'Current release', null],
            ['stage', InputArgument::REQUIRED, 'Deploy stage', null],
        ];
    }
}
