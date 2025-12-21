<?php
// Force override configuration BEFORE Laravel loads
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_DRIVER'] = 'array';
$_ENV['QUEUE_CONNECTION'] = 'sync';

// Prevent any database session queries
if (!function_exists('disable_database_sessions')) {
    function disable_database_sessions() {
        // Override the session handler
        class_alias(\Illuminate\Session\CookieSessionHandler::class, \Illuminate\Session\DatabaseSessionHandler::class);
    }
}

disable_database_sessions();