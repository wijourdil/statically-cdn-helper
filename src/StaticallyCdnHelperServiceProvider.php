<?php

namespace Wijourdil\Statically;

use Illuminate\Support\ServiceProvider;

class StaticallyCdnHelperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/statically-cdn.php',
            'statically-cdn'
        );
    }
}
