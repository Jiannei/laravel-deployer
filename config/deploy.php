<?php

return [
    'bin' => 'vendor/bin/dep',

    'file' => [
        'path' => env('DEPLOY_FILE_PATH', storage_path('app/deploy')),
        'name' => env('DEPLOY_FILE_NAME', 'deploy.yaml'),
    ],

    'log' => [
        'enable' => env('DEPLOY_LOG_ENABLE',true),
        'path' => storage_path('logs'),
    ],

    'profile' => [
        'enable' => env('DEPLOY_PROFILE_ENABLE',true),
        'path' => storage_path('logs'),
    ],
];
