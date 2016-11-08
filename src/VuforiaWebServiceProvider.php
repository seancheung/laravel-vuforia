<?php

namespace Eyesar\Vuforia;

use Illuminate\Support\ServiceProvider;

class VuforiaWebServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/vws.php', 'vws');

        $this->app->singleton(VuforiaWebService::class, function ($app) {
            return VuforiaWebService::create(config('vws'));
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/vws.php' => config_path('vws.php')
        ]);
    }
}
