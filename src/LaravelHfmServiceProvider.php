<?php

namespace AnatolieGhebea\LaravelHfm;

use Illuminate\Support\ServiceProvider;
use AnatolieGhebea\LaravelHfm\Commands\LaravelHfmCommand;

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

            $migrationFileName = 'create_laravel_hfm_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                LaravelHfmCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-hfm');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-hfm.php', 'laravel-hfm');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
