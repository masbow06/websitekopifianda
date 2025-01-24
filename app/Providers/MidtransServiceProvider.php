<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\MidtransService;

class MidtransServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MidtransService::class, function ($app) {
            return new MidtransService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
