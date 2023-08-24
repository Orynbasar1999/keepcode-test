<?php

namespace App\Providers;

use App\Services\ProductPurchaseCreator;
use App\Services\ProductRentHandler;
use App\Services\UserCreator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserCreator::class, function () {
            return new UserCreator();
        });
        $this->app->singleton(ProductPurchaseCreator::class, function () {
            return new ProductPurchaseCreator();
        });
        $this->app->singleton(ProductRentHandler::class, function () {
            return new ProductRentHandler();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
