<?php

/*
 * This file is part of the Jiannei/laravel-deployer.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Deployer;

set('workspace', function () {
    // We make this option required by throwing an exception if it's not overriden.
    throw new \RuntimeException('Please set up the "workspace" option.');
});

set('application', function () {
    throw new \RuntimeException('Please set up the "application" option.');
});

set('app_path', function () {
    throw new \RuntimeException('Please set up the "app_path" option.');
});

set('webhook_url', function () {
    throw new \RuntimeException('Please set up the "webhook_url" option.');
});

task('webhook:start', sendWebhook('start'));

task('webhook:build', sendWebhook('build'));

task('webhook:success', sendWebhook('success'));

task('webhook:fail', sendWebhook('fail'));

task('webhook:done', sendWebhook('done'));

function sendWebhook(string $stage)
{
    return function () use ($stage) {
        runLocally("cd {{app_path}} && {{bin/php}} artisan dep:webhook {{id}} {{workspace}} {{application}} {{release_name}} $stage");
    };
}
