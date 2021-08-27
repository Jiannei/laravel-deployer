<?php

namespace Jiannei\LaravelDeployer\Support;

use Symfony\Component\Process\Process;

trait Shellable
{
    /**
     * 执行 shell 命令.
     *
     * @param  string  $command
     * @return int|null
     */
    protected function exec(string $command): ?int
    {
        return Process::fromShellCommandline($command)
            ->setTty($this->isTtySupported())
            ->setWorkingDirectory(base_path())
            ->setTimeout(null)
            ->setIdleTimeout(null)
            ->mustRun()
            ->getExitCode();
    }

    protected function isTtySupported(): bool
    {
        return config('app.env') !== 'testing' && Process::isTtySupported();
    }
}