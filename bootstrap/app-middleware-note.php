<?php

// Tambahkan di bootstrap/app.php, di dalam ->withMiddleware(function (Middleware $middleware) { ... })

use App\Http\Middleware\RestrictDemoWrites;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'restrict.demo' => RestrictDemoWrites::class,
    ]);
})
