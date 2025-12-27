<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request; 
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Force cookie sessions on application boot
        Config::set('session.driver', 'cookie');
        Config::set('cache.default', 'array');
        Config::set('queue.default', 'sync');
        
        // Fix cache paths for Railway
        Config::set('view.compiled', '/tmp/views');
        
        // Ensure directories exist
        if (!is_dir('/tmp/views')) {
            mkdir('/tmp/views', 0755, true);
        }
    }
    
    public function boot()
    {
        // Double-check session driver
        if (config('session.driver') === 'database') {
            config(['session.driver' => 'cookie']);
        }
         RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

  

}