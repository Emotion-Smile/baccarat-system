<?php

namespace App\Kravanh\Domain\Integration\Providers;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\Integration\Services\AF88Service;
use App\Kravanh\Domain\Integration\Services\T88Service;
use App\Providers\AppServiceProvider;

class IntegrationServiceProvider extends AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(T88Contract::class, T88Service::class);
        $this->app->bind(AF88Contract::class, AF88Service::class);
    }

    public function boot(): void
    {
        $this->registerConfig();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'integration');
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/t88.php' => config_path('t88.php'),
            __DIR__ . '/../Config/af88.php' => config_path('af88.php')
        ], 'config');
        
        $this->mergeConfigFrom(__DIR__ . '/../Config/t88.php', 't88');
        $this->mergeConfigFrom(__DIR__ . '/../Config/af88.php', 'af88');
    }
}