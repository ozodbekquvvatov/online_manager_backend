<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Check if we're in Railway environment
        if (env('RAILWAY_ENVIRONMENT', false) || env('APP_ENV') === 'production') {
            // Railway-specific optimizations
            Config::set('view.compiled', '/tmp/views');
            
            // Create cache directory if it doesn't exist
            if (!is_dir('/tmp/views')) {
                @mkdir('/tmp/views', 0755, true);
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix MySQL connection issues - add reconnection logic
        try {
            DB::reconnect('mysql');
            
            // Handle MySQL gone away errors
            DB::connection('mysql')->setReconnector(function ($connection) {
                if ($connection instanceof Connection && 
                    $connection->getPdo() === null) {
                    try {
                        $connection->reconnect();
                    } catch (\Exception $e) {
                        // Log error but don't crash
                        error_log('MySQL reconnection failed: ' . $e->getMessage());
                    }
                }
            });
        } catch (\Exception $e) {
            // Silently handle DB connection errors during boot
            error_log('Database connection issue during boot: ' . $e->getMessage());
        }
        
        // IMPORTANT: Don't force 'cookie' session driver if package is missing
        // Instead, check if we can use it, otherwise fallback to 'file'
        $sessionDriver = config('session.driver');
        
        // If trying to use cookie sessions but package might be missing
        if ($sessionDriver === 'cookie') {
            // Check if we can actually use cookie sessions
            if (!class_exists('Illuminate\Session\CookieSessionHandler')) {
                // Fallback to file sessions
                Config::set('session.driver', 'file');
                Config::set('session.lifetime', 120);
                Config::set('session.expire_on_close', false);
            }
        }
        
        // Configure rate limiting for API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        
        // Additional Railway optimizations
        if (env('RAILWAY_ENVIRONMENT', false)) {
            // Optimize for Railway's ephemeral filesystem
            $this->optimizeForRailway();
        }
    }
    
    /**
     * Railway-specific optimizations
     */
    protected function optimizeForRailway(): void
    {
        // Use array cache for better performance in Railway
        if (config('cache.default') === 'file') {
            Config::set('cache.default', 'array');
        }
        
        // Ensure session directory exists
        $sessionPath = config('session.files', storage_path('framework/sessions'));
        if (!is_dir($sessionPath)) {
            @mkdir($sessionPath, 0755, true);
        }
        
        // Log cache path for debugging
        error_log('Cache path: ' . config('cache.stores.file.path', storage_path('framework/cache')));
        error_log('Session path: ' . $sessionPath);
    }
}