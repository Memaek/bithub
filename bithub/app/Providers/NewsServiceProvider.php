<?php

namespace App\Providers;


use App\Service\EmailService;
use App\Service\NewsService;
use App\Service\UserService;
use Illuminate\Support\ServiceProvider;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(NewsService::class, function ($app){
            return new NewsService();
        });
    }

}
