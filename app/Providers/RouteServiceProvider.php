<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    protected array $games = [
        'dragon-tiger', 
        'baccarat'
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            collect($this->games)->each(function ($game) {
                Route::prefix("casino/{$game}")
                    ->middleware([
                        'web',
                        'auth:sanctum',
                    ])
                    ->namespace($this->namespace)
                    ->group(base_path("routes/{$game}/web.php"));


                Route::prefix("casino/{$game}/api/v1/")
                    ->middleware([
                        'web',
                        'auth:sanctum'
                    ])
                    ->namespace($this->namespace)
                    ->group(base_path("routes/{$game}/api.php"));


                Route::prefix("/nova-api/{$game}")
                    ->middleware(['nova'])
                    ->group(base_path("routes/{$game}/nova.php"));
            });
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(appGetSetting('api_rate_limit', 2000))->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
