<?php

namespace App\Kravanh\Domain\IpInfo;


use Illuminate\Support\ServiceProvider;

class IpInfoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            IpInfoServiceInterface::class,
            IpInfoService::class
        );
    }

    public function boot(): void
    {
        $domainPath = app()->basePath() . '/app/Kravanh/Domain/IpInfo/';

        $this->registerConfig($domainPath);
        $this->loadMigrationsFrom("{$domainPath}Database/migrations");
    }

    protected function registerConfig($domainPath): void
    {
        //app_path('Kravanh/Domain/Game/Config/config.php')

        $this->publishes([
            "{$domainPath}Config/ipinfo.php" => config_path('ipinfo' . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            "{$domainPath}Config/ipinfo.php", 'ipinfo'
        );
    }
}
