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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Jiannei\LaravelDeployer\Jobs\DeployJob;
use Jiannei\LaravelDeployer\Support\Shellable;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Deploy extends Command
{
    use Shellable;

    protected $name = 'dep';

    protected $description = 'Deploy project.';

    protected $depId;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->depId = Str::uuid();

        try {
            $depBinary = $this->getDeployBinary();
            $depCommand = $this->argument('action') ?: '';
            $depOptions = $this->getDeployOptions($this->option('workspace'), $this->option('project'));

            if ($depCommand === 'deploy' && strtobool($this->option('async'))) {
                dispatch(new DeployJob($depBinary, $depOptions))
                    ->onConnection($this->option('connection'))
                    ->onQueue($this->option('queue'));

                return 0;
            }

            $command = "$depBinary $depCommand $depOptions";

            $this->line("[<info>command</info>] $command");

            return $this->exec($command);
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());
        }

        return 0;
    }

    protected function getDeployBinary(): string
    {
        return config('deploy.bin', 'vendor/bin/dep');
    }

    protected function getDeployOptions($workspace, $project): string
    {
        $options = [];

        if ($this->getOutput()->isDebug()) {
            $options[] = '-vvv';
        } elseif ($this->getOutput()->isVeryVerbose()) {
            $options[] = '-vv';
        } elseif ($this->getOutput()->isVerbose()) {
            $options[] = '-v';
        } elseif ($this->getOutput()->isQuiet()) {
            $options[] = '-q';
        }

        $options[] = $this->getDeployFileOption($workspace, $project);

        if ($this->argument('action') === 'deploy') {
            $options[] = "--option=id={$this->depId}";
            $options[] = $this->getDeployLogOption();
            $options[] = $this->getDeployProfileOption();
        }

        return implode(' ', $options);
    }

    protected function getDeployFileOption($workspace, $project): string
    {
        $filePath = config('deploy.file.path');
        $fileName = config('deploy.file.name');

        $file = $filePath.DIRECTORY_SEPARATOR.$fileName;
        if ($workspace && $project) {
            $file = $filePath."/{$workspace}-{$project}-{$fileName}";
        }

        if (! File::exists($file)) {
            throw new \RuntimeException('No deploy config file exists');
        }

        return '--file='.$file;
    }

    protected function getDeployLogOption(): string
    {
        if (! config('deploy.log.enable') || ! strtobool($this->option('log'))) {
            return '';
        }

        return '--log='.config('deploy.log.path')."/deploy-{$this->depId}.log";
    }

    protected function getDeployProfileOption(): string
    {
        if (! config('deploy.profile.enable') || ! strtobool($this->option('profile'))) {
            return '';
        }

        return '--profile='.config('deploy.profile.path')."/deploy-{$this->depId}.profile";
    }

    protected function getArguments(): array
    {
        return [
            ['action', InputArgument::OPTIONAL, 'Deployer action', null],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['workspace', '-W', InputOption::VALUE_REQUIRED, 'Workspace', null],
            ['project', '-P', InputOption::VALUE_REQUIRED, 'Project', null],
            ['log', '-L', InputOption::VALUE_OPTIONAL, 'Write log to a file', false],
            ['profile', '', InputOption::VALUE_OPTIONAL, 'Write profile to a file', false],
            ['async', '-A', InputOption::VALUE_OPTIONAL, 'Asynchronous deploy?', false],
            ['queue', '-Q', InputOption::VALUE_OPTIONAL, 'Set the desired queue for deploy job', 'deploy'],
            ['connection', '-C', InputOption::VALUE_OPTIONAL, 'Set the desired connection for deploy job', config('queue.default')],
        ];
    }
}
