<?php

namespace App\Providers;

use App\Components\PostList;
use App\Components\PostSave;
use App\Services\PostListInterface;
use App\Services\PostSaveInterface;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostListInterface::class, function ($app) {
            return new PostList();
        });
        $this->app->bind(PostSaveInterface::class, function ($app) {
            return new PostSave();
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
