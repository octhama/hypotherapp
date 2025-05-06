<?php

namespace App\Http;

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // Autres middlewares...
        'admin' => AdminMiddleware::class,
    ];
}

