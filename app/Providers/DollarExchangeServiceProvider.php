<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

use App\Repositories\DollarExchangeRepository;



class DollarExchangeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        // $this->app->bind(DollarExchangeRepository::class, function ($app) {
        //     return new DollarExchangeRepository();
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(DollarExchangeRepository $dollar_repo)
    {
        if (!App::runningInConsole()) {
            // View::share('dollar_exchange', $dollar_repo->getLast()); 
        }
    }
}
