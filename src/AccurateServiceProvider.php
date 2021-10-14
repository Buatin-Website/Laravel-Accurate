<?php

namespace Buatin\Accurate;

use Illuminate\Support\ServiceProvider;

class AccurateServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'buatin');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'buatin');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Publishing is only necessary when using the CLI.
        // if ($this->app->runningInConsole()) {
        //     $this->bootForConsole();
        // }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/accurate.php', 'accurate');

        // Register the service the package provides.
        $this->app->singleton('accurate', function () {
            return new Accurate;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['accurate'];
    }

}
