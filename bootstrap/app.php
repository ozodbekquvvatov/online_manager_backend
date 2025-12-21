<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->web([
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // API middleware - CORS qo'shish
        $middleware->api([
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            HandleCors::class, // CORS middleware
        ]);

        // Route middleware'larni qo'shish
        $middleware->alias([
            'auth' => \App\Http\Middleware\AuthMiddleware::class,
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'guest' => \App\Http\Middleware\AuthMiddleware::class,
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'auth.admin' => \App\Http\Middleware\AdminTokenAuth::class,
            'cors' => \App\Http\Middleware\Cors::class,
            'api' => [\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, HandleCors::class, 'throttle:api', \Illuminate\Routing\Middleware\SubstituteBindings::class,],
            'api-no-throttle' => [
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],
        ]);

        // CSRF'dan istisna qilish (API uchun)
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'admin/login',
            'sanctum/csrf-cookie'
        ]);

        // CORS sozlamalari
        $middleware->appendToGroup('api', [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
