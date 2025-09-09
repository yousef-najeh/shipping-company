<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        // then: function () {
        //     require base_path('routes/users.php');
        //     require base_path('routes/admin.php');
        //     require base_path('routes/employee.php');
        //     require base_path('routes/manager.php');
        //     require base_path('routes/driver.php');
        //     require base_path('routes/client.php');
        //     require base_path('routes/vendor_shop.php');
        //     require base_path('routes/shipment.php');
        //     require base_path('routes/item.php');
        //     require base_path('routes/order.php');
        //     require base_path('routes/auth.php');
        // }

        then: function () {
        Route::middleware('api')
            ->name('auth')
            ->group(base_path('routes/auth.php'));
        },    
    )
    
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
        $middleware->alias([
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
