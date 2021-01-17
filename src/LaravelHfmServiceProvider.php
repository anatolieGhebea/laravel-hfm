<?php

namespace Ghebby\LaravelHfm;

use Illuminate\Support\ServiceProvider;

class LaravelHfmServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-hfm.php' => config_path('laravel-hfm.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-hfm'),
            ], 'views');
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-hfm');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-hfm.php', 'laravel-hfm');
    }
}
