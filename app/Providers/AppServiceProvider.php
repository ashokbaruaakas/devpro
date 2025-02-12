<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Configuration;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->singleton(Configuration::class, fn (): Configuration => Configuration::make());
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
