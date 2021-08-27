<?php

/*
 * This file is part of the Jiannei/laravel-deployer.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\LaravelDeployer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jiannei\LaravelDeployer\Support\Shellable;
use Throwable;

class DeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,Shellable;

    private $depBinary;
    private $depOptions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $depBinary, string $depOptions)
    {
        $this->depBinary = $depBinary;
        $this->depOptions = $depOptions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $command = "{$this->depBinary} deploy {$this->depOptions}";

        $this->exec($command);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     * @throws Throwable
     */
    public function failed(Throwable $exception)
    {
        $command = "{$this->depBinary} deploy:unlock {$this->depOptions}";

        $this->exec($command);

        throw $exception;
    }

    protected function isTtySupported(): bool
    {
        return false;
    }
}
