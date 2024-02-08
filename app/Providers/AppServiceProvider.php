<?php

namespace App\Providers;

use App\Kravanh\Domain\Integration\Exceptions\T88Exception;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Http\Controllers\LoginController;
use Spatie\LaravelIgnition\Facades\Flare;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        //        Flare::filterExceptionsUsing(
        //            fn(Throwable $throwable) => !$throwable instanceof InsufficientFunds
        //        );

        Flare::filterExceptionsUsing(function (Throwable $throwable) {
            $excludedExceptions = [
                InsufficientFunds::class,
                T88Exception::class,
                LoginController::class,
            ];

            foreach ($excludedExceptions as $excludedException) {
                if ($throwable instanceof $excludedException) {
                    return false;
                }
            }

            return true;
        });
    }
}
