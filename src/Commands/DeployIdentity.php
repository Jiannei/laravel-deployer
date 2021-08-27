<?php

namespace Jiannei\LaravelDeployer\Commands;

use Illuminate\Console\Command;
use Jiannei\LaravelDeployer\Support\Shellable;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeployIdentity extends Command
{
    use Shellable;

    protected $name = 'dep:identity';

    protected $description = 'Generate Deploy identity key.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $deployPath = config('deploy.file.path');
        $identityFileName = $this->option('identity');
        $remoteHost = $this->argument('host');
        $remoteUser = $this->argument('user');

        $this->generateIdentity($deployPath, $identityFileName);

        $this->copyPubIdentity($deployPath, $identityFileName, $remoteHost, $remoteUser);

        if (strtobool($this->option('test'))) {
            $this->testIdentity($deployPath, $identityFileName, $remoteHost, $remoteUser);
        }

        return 0;
    }

    protected function generateIdentity($deployPath, $identityFileName)
    {
        $file = $deployPath.DIRECTORY_SEPARATOR.$identityFileName;

        $this->exec("ssh-keygen -t rsa -b 4096 -f $file");
    }

    protected function copyPubIdentity($deployPath, $identityFileName, $remoteHost, $remoteUser)
    {
        $pubKey = $deployPath.DIRECTORY_SEPARATOR."$identityFileName.pub";

        $this->exec("ssh-copy-id -i $pubKey $remoteUser@$remoteHost");
    }

    protected function testIdentity($deployPath, $identityFileName, $remoteHost, $remoteUser)
    {
        $priKey = $deployPath.DIRECTORY_SEPARATOR.$identityFileName;

        $this->exec("ssh $remoteUser@$remoteHost -i $priKey");
    }

    protected function getArguments(): array
    {
        return [
            ['host', InputArgument::REQUIRED, 'Remote server host name or ip address', null],
            ['user', InputArgument::REQUIRED, 'Remote server user name', null],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['identity', '-I', InputOption::VALUE_REQUIRED, 'Identity File name', 'identity'],
            ['test', '-T', InputOption::VALUE_REQUIRED, 'make a test ?', false],
        ];
    }
}
