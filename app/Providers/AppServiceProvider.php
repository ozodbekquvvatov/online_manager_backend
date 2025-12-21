<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Fix for Railway: Set cache paths to writable locations
        $this->app->booting(function () {
            $cachePath = '/tmp/views';
            
            // Ensure the directory exists and is writable
            if (!is_dir($cachePath)) {
                mkdir($cachePath, 0755, true);
            }
            
            // Set view cache path
            Config::set('view.compiled', $cachePath);
            
            // Also fix other cache paths if needed
            Config::set('cache.stores.file.path', '/tmp/cache');
        });
    }
    
    public function boot()
    {
        //
    }
}