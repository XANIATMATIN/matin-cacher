<?php

namespace MatinUtils\MatinCacher;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('matin-cacher', function ($app) {
            $archiver = new Cacher;
            return $archiver;
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function provides()
    {
        return [];
    }
}
