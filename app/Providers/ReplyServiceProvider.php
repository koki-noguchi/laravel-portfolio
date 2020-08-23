<?php

namespace App\Providers;

use App\Components\ReplySave;
use App\Services\ReplyInterface;
use Illuminate\Support\ServiceProvider;

class ReplyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReplyInterface::class, function ($app) {
            return new ReplySave();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
