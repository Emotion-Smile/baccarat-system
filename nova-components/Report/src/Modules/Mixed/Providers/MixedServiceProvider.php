<?php

namespace KravanhEco\Report\Modules\Mixed\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use KravanhEco\Report\Http\Middleware\Authorize;

class MixedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/report/mixed')
            ->group(__DIR__.'/../Routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}