<?php

namespace Zeevx\LaravelOvalfi;

use Illuminate\Support\ServiceProvider;
use Zeevx\LaravelOvalfi\Command\LaravelOvalfiCommand;

class LaravelOvalfiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        if (file_exists($file = __DIR__.'/helper.php')) {
            require $file;
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                LaravelOvalfiCommand::class,
            ]);
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('ovalfi.php'),
            ], 'laravel-ovalfi-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'ovalfi');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-ovalfi', function () {
            return new LaravelOvalfi;
        });
    }
}
