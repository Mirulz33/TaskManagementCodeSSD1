<?php

namespace App\Providers;



use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Failed;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\LogLoginActivity;


class AppServiceProvider extends ServiceProvider

{
    protected $listen = [
        Login::class => [
            LogLoginActivity::class,
        ],
        Failed::class => [
            LogLoginActivity::class,
        ],
    ];
}
