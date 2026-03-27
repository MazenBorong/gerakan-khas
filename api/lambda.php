<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

/** @var Application $app */
if (getenv('VERCEL')) {
    $storage = '/tmp/gk-laravel-storage';
    foreach ([
        'framework',
        'framework/cache',
        'framework/cache/data',
        'framework/sessions',
        'framework/views',
        'logs',
    ] as $dir) {
        $path = $storage.'/'.$dir;
        if (! is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
    $app->useStoragePath($storage);
}

$app->handleRequest(Request::capture());
