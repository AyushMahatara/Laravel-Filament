<?php

namespace App\Providers;

use App\Events\PromoteStudent;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    // public function register(): void
    // {
    //     //
    // }


    protected $listen = [
        PromoteStudent::class => []
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
