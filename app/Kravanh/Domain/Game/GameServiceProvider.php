<?php

namespace App\Kravanh\Domain\Game;

use App\Providers\AppServiceProvider;

class GameServiceProvider extends AppServiceProvider
{

    public function register(): void
    {
        $this->app->bind(IGameService::class, GameService::class);
    }

    public function boot(): void
    {

        $domainPath = $this->app->basePath('/app/Kravanh/Domain/Game/');
        $this->loadMigrationsFrom("$domainPath/Database/migrations");
    }


}
