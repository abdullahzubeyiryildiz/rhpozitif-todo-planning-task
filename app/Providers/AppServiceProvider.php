<?php

namespace App\Providers;

use App\Services\TaskService;
use App\Services\TaskDistributor;
use App\Providers\TaskProviderOne;
use App\Providers\TaskProviderTwo;
use App\Services\DeveloperService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
