<?php

namespace App\Providers;

use App\Service\UserService;
use App\Service\WalletService;
use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(WalletService::class, function ($app) {
            return new WalletService($app->make(UserService::class));
        });
    }
}
