<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;

/**
 * Vercel Composer "vercel" script — runs after `npm run build` and asset copy.
 *
 * By default we do NOT run migrations here so the Vercel build cannot fail on DB
 * (firewalls, missing APP_KEY during build, etc.). Run migrations locally or in CI:
 *
 *   php artisan migrate --force
 *
 * To run migrations on deploy, set in Vercel: RUN_MIGRATE_ON_VERCEL=1
 * (still requires working DB_* + APP_KEY in the Vercel environment.)
 */
$root = dirname(__DIR__);

$skip = getenv('SKIP_MIGRATE_ON_VERCEL') ?: '';
if ($skip === '1' || strtolower($skip) === 'true') {
    fwrite(STDOUT, "[vercel] SKIP_MIGRATE_ON_VERCEL=1 — skipping migrations.\n");

    exit(0);
}

$run = getenv('RUN_MIGRATE_ON_VERCEL') ?: '';
if ($run !== '1' && strtolower($run) !== 'true') {
    fwrite(STDOUT, "[vercel] Migrations skipped (default). Set RUN_MIGRATE_ON_VERCEL=1 to migrate on deploy, or run `php artisan migrate --force` locally.\n");

    exit(0);
}

require $root.'/vendor/autoload.php';

$app = require $root.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

passthru('php '.escapeshellarg($root.'/artisan').' migrate --force --no-interaction --graceful', $code);

exit($code);
