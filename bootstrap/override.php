<?php
// Force-override session and cache drivers
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_DRIVER'] = 'array';
$_ENV['QUEUE_CONNECTION'] = 'sync';

// Disable database if connection fails
if (!function_exists('override_database')) {
    function override_database() {
        // Temporarily disable database connection attempts
        class_alias(Illuminate\Support\Facades\Cache::class, 'DB');
    }
}

// Prevent session database queries
if (!function_exists('prevent_session_db')) {
    function prevent_session_db() {
        // Replace DatabaseSessionHandler with a dummy
        if (class_exists('Illuminate\Session\DatabaseSessionHandler')) {
            class_alias('Illuminate\Session\FileSessionHandler', 'Illuminate\Session\DatabaseSessionHandler');
        }
    }
}

override_database();
prevent_session_db();