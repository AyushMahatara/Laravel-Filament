<?php

namespace App\Providers;

use App\Events\PromoteStudent;
use App\Listeners\SendPromotedEmail;
use App\Listeners\UpdateStudent;
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
        PromoteStudent::class => [
            UpdateStudent::class,
            SendPromotedEmail::class
        ]
    ];
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
