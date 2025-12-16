    <?php

    use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;
    use App\Http\Middleware\AuthMiddleware;
    use Fruitcake\Cors\HandleCors;


    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            api: __DIR__ . '/../routes/api.php',
            commands: __DIR__ . '/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware): void {
            $middleware->alias([
                'auth.admin' => \App\Http\Middleware\AdminTokenAuth::class,
                'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
                'cors' => \Illuminate\Http\Middleware\HandleCors::class, // <-- shu qator qo‘shiladiD
            ]);
        })
        ->withExceptions(function (Exceptions $exceptions): void {
            //
        })->create();
