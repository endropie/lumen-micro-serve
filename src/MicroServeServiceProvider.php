<?php

namespace Endropie\LumenMicroServe;

use Illuminate\Support\ServiceProvider;

class MicroServeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('http', function ($app) {
            return new \Illuminate\Http\Client\Factory;
        });
    }

    public function boot(): void
    {
        //
    }
}
