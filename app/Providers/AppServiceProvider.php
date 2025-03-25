<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void {}


    public function boot(): void
    {
        app()->setLocale('vi');

        if (app()->environment('local')) {
            Artisan::call('l5-swagger:generate');
        }
    }
}
