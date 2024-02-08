<?php

namespace KravanhEco\LaravelCmd;

use Illuminate\Support\ServiceProvider;
use KravanhEco\LaravelCmd\Console\ActionMakeCommand;
use KravanhEco\LaravelCmd\Console\CollectionMakeCommand;
use KravanhEco\LaravelCmd\Console\ControllerMakeCommand;
use KravanhEco\LaravelCmd\Console\DtoMakeCommand;
use KravanhEco\LaravelCmd\Console\EventMakeCommand;
use KravanhEco\LaravelCmd\Console\MiddlewareMakeCommand;
use KravanhEco\LaravelCmd\Console\ModelMakeCommand;
use KravanhEco\LaravelCmd\Console\NovaActionCommand;
use KravanhEco\LaravelCmd\Console\NovaFilterCommand;
use KravanhEco\LaravelCmd\Console\NovaLensCommand;
use KravanhEco\LaravelCmd\Console\NovaPartitionCommand;
use KravanhEco\LaravelCmd\Console\NovaResourceCommand;
use KravanhEco\LaravelCmd\Console\NovaTrendCommand;
use KravanhEco\LaravelCmd\Console\NovaValueCommand;
use KravanhEco\LaravelCmd\Console\ObserverMakeCommand;
use KravanhEco\LaravelCmd\Console\PolicyMakeCommand;
use KravanhEco\LaravelCmd\Console\QueryBuilderMakeCommand;
use KravanhEco\LaravelCmd\Console\QueryMakeCommand;
use KravanhEco\LaravelCmd\Console\RequestMakeCommand;
use KravanhEco\LaravelCmd\Console\StateMakeCommand;
use KravanhEco\LaravelCmd\Console\SubscriberMakeCommand;
use KravanhEco\LaravelCmd\Console\ViewModelMakeCommand;

class LaravelCmdServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'kravanh-eco');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'kravanh-eco');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-cmd.php', 'laravel-cmd');

        // Register the service the package provides.
        $this->app->singleton('laravel-cmd', function ($app) {
            return new LaravelCmd;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-cmd'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/laravel-cmd.php' => config_path('laravel-cmd.php'),
        ], 'laravel-cmd.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/kravanh-eco'),
        ], 'laravel-cmd.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/kravanh-eco'),
        ], 'laravel-cmd.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/kravanh-eco'),
        ], 'laravel-cmd.views');*/

        // Registering package commands.
        $this->commands([
            ViewModelMakeCommand::class,
            RequestMakeCommand::class,
            ControllerMakeCommand::class,
            PolicyMakeCommand::class,
            MiddlewareMakeCommand::class,
            QueryMakeCommand::class,
            DtoMakeCommand::class,
            ObserverMakeCommand::class,
            ActionMakeCommand::class,
            CollectionMakeCommand::class,
            EventMakeCommand::class,
            QueryBuilderMakeCommand::class,
            StateMakeCommand::class,
            SubscriberMakeCommand::class,
            NovaValueCommand::class,
            NovaActionCommand::class,
            NovaFilterCommand::class,
            NovaLensCommand::class,
            NovaPartitionCommand::class,
            NovaTrendCommand::class,
            NovaResourceCommand::class,
            PolicyMakeCommand::class,
            ModelMakeCommand::class
        ]);
    }
}
