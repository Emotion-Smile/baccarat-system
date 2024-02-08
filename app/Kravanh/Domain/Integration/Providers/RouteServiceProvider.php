<?php

namespace App\Kravanh\Domain\Integration\Providers;

use App\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            Route::prefix('api/integration')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(__DIR__ . '/../Routes/api.php');

            Route::prefix('integration')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(__DIR__ . '/../Routes/web.php');

            Route::prefix('/nova-api/integration')
                ->middleware(['nova'])
                ->group(__DIR__.'/../Routes/nova.php');
        });
    }
}