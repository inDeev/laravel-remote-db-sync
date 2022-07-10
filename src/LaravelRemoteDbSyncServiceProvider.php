<?php

namespace Indeev\LaravelRemoteDbSync;

use Illuminate\Support\ServiceProvider;
use Indeev\LaravelRemoteDbSync\Console\Commands\RemoteDbSync;

class LaravelRemoteDbSyncServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-remote-db-sync');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-remote-db-sync');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-remote-db-sync.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-remote-db-sync'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-remote-db-sync'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-remote-db-sync'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                RemoteDbSync::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-remote-db-sync');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-remote-db-sync', function () {
            return new LaravelRemoteDbSync;
        });
    }
}
