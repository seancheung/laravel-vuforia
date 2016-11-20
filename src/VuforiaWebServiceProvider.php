<?php

namespace Panoscape\Vuforia;

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
        $this->mergeConfigFrom(__DIR__ . '/config/vws.php', 'vws');

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
            __DIR__.'/config/vws.php' => config_path('vws.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/stubs/VuforiaJob.stub' => app_path('Jobs/VuforiaJob.php')
        ], 'jobs');

        $this->publishes([
            __DIR__.'/stubs/VuforiaNotification.stub' => app_path('Notifications/VuforiaNotification.php')
        ], 'notifications');
    }
}
