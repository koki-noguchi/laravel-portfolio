<?php

namespace App\Providers;

use App\Components\MessageSave;
use App\Services\MessageInterface;
use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MessageInterface::class, function ($app) {
            return new MessageSave();
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
