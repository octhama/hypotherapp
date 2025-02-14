<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // Autres middlewares...
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}

