<?php

namespace App\Providers;

use App\Components\PhotoSaveDelete;
use App\Services\PhotoInterface;
use Illuminate\Support\ServiceProvider;

class PhotoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PhotoInterface::class, function ($app) {
            return new PhotoSaveDelete();
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
