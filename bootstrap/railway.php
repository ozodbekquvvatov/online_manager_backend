<?php

// Railway-specific fixes
$cachePath = '/tmp/views';
$cacheDir = dirname($cachePath);

// Create necessary directories with proper permissions
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/framework/views',
    '/tmp/framework/cache',
    '/tmp/framework/sessions',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Set environment variables if not set
if (!isset($_ENV['VIEW_COMPILED_PATH'])) {
    $_ENV['VIEW_COMPILED_PATH'] = $cachePath;
    $_ENV['CACHE_DRIVER'] = 'array';
    $_ENV['SESSION_DRIVER'] = 'cookie';
}