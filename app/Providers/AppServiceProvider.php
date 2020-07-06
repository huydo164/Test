<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
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
    public function boot(){
        if(env('IS_HTTPS')){
            URL::forceRootUrl(Config::get('app.url'));
            if (str_contains(Config::get('app.url'), 'https://')) {
                URL::forceScheme('https');
            }
        }
    }
}
