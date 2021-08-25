<?php

/*
 * This file is part of the Jiannei/laravel-deployer.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jiannei\LaravelDeployer\Events;

class DeployHookEvent
{
    public $id;
    public $workspace;
    public $project;
    public $release;
    public $stage;

    public function __construct($id, $workspace, $project, $release, $stage)
    {
        $this->id = $id;
        $this->workspace = $workspace;
        $this->project = $project;
        $this->release = $release;
        $this->stage = $stage;
    }
}
