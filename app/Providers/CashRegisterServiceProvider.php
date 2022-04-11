<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;

use App\Repositories\CashRegisterRepository;



class CashRegisterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(CashRegisterRepository::class, function ($app) {
            return new CashRegisterRepository();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(CashRegisterRepository $dollar_repo)
    {
        if (!App::runningInConsole()) {
            View::share('dollar_exchange', $dollar_repo->getLast()); 
        }
    }
}
