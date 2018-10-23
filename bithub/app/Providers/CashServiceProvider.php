<?php

namespace App\Providers;

use App\Models\Bank;
use App\Service\CashService;
use App\Service\WalletService;
use Illuminate\Support\ServiceProvider;

class CashServiceProvider extends ServiceProvider
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
        $this->app->singleton(CashService::class, function ($app) {
            return new CashService();
        });
    }
}
