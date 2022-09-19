<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\Client;
use App\Traits\UUID;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    //use UUID;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = Str::uuid()->toString();
        });
        
        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });
    }
    
}
