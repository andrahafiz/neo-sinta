<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
    }
}
