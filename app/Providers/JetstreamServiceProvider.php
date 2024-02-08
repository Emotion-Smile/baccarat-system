<?php

namespace App\Providers;

use App\Kravanh\Domain\User\Actions\FrontendAuthenticatorAction;
use App\Kravanh\Domain\User\Actions\LoginScreenRenderAction;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class JetstreamServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::loginView(LoginScreenRenderAction::handle());
        Fortify::authenticateUsing(FrontendAuthenticatorAction::handle());
    }
}
