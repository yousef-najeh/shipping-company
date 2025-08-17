<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function () {
            require base_path('routes/users.php');
            require base_path('routes/admin.php');
            require base_path('routes/employee.php');
            require base_path('routes/manager.php');
            require base_path('routes/driver.php');
            require base_path('routes/client.php');
            require base_path('routes/vendor_shop.php');
            require base_path('routes/shipment.php');
            require base_path('routes/item.php');
        
        }   
    )
    
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
