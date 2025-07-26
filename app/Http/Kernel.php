<?php
// File: app/Http/Kernel.php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // existing middleware...
        \App\Http\Middleware\Cors::class, // Tambahkan ini
    ];

    // Atau di $middlewareGroups
    protected $middlewareGroups = [
        'web' => [
            // existing middleware...
            \App\Http\Middleware\Cors::class, // Tambahkan ini
        ],
    ];
}