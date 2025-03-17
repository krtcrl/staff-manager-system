<?php

namespace App\Providers;

use App\Services\RequestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RequestService::class, function ($app) {
            return new RequestService();
        });
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.timezone' => 'Asia/Singapore']); // Set timezone explicitly
        date_default_timezone_set('Asia/Singapore'); // Ensure PHP uses correct timezone
    }
}
