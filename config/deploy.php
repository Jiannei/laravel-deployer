<?php

/*
 * This file is part of the Jiannei/laravel-deployer.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'bin' => 'vendor/bin/dep',

    'recipes' => [
        'path' => dirname(__DIR__).PATH_SEPARATOR.'recipes',
    ],

    'file' => [
        'path' => env('DEPLOY_FILE_PATH', storage_path('app/deploy')),
        'name' => env('DEPLOY_FILE_NAME', 'deploy.yaml'),
    ],

    'log' => [
        'enable' => env('DEPLOY_LOG_ENABLE', true),
        'path' => storage_path('logs'),
    ],

    'profile' => [
        'enable' => env('DEPLOY_PROFILE_ENABLE', true),
        'path' => storage_path('logs'),
    ],
];
