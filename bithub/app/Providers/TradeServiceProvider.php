<?php

namespace App\Providers;

use App\Models\TradeOrder;
use App\Service\TradeService;
use App\Service\UserService;
use App\Service\WalletService;
use Illuminate\Support\ServiceProvider;

class TradeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(UserService $userService, WalletService $walletService)
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
        $this->app->singleton(TradeService::class, function ($app) {
            return new TradeService($app->make(UserService::class),
                                    $app->make(WalletService::class));
        });
    }
}
