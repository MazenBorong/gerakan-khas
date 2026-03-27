<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;

/**
 * Vercel build hook: run migrations after frontend build.
 *
 * Set SKIP_MIGRATE_ON_VERCEL=1 if your DB host blocks Vercel IPs or you prefer
 * running: php artisan migrate --force locally against production.
 */
$root = dirname(__DIR__);

require $root.'/vendor/autoload.php';

$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$skip = getenv('SKIP_MIGRATE_ON_VERCEL') ?: '';
if ($skip === '1' || strtolower($skip) === 'true') {
    fwrite(STDOUT, "[vercel] SKIP_MIGRATE_ON_VERCEL is set; skipping migrations.\n");

    exit(0);
}

passthru('php '.escapeshellarg($root.'/artisan').' migrate --force --no-interaction', $code);

exit($code);
