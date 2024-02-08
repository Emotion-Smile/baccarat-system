<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class VaporUiServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->gate();
    }

    protected function gate(): void
    {
        Gate::define('viewVaporUI', function ($user) {
            return true;
        });
    }


    public function register(): void
    {
    }
}
