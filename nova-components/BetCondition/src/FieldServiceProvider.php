<?php

namespace Kravanh\BetCondition;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use KravanhEco\Report\Http\Middleware\Authorize;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('bet-condition', __DIR__ . '/../dist/js/field.js');
            Nova::style('bet-condition', __DIR__ . '/../dist/css/field.css');
        });

        $this->app->booted(function () {
            $this->routes();
        });
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
            ->prefix('nova-vendor/bet-condition')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
