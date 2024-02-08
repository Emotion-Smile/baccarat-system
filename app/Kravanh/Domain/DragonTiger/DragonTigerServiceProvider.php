<?php

namespace App\Kravanh\Domain\DragonTiger;

use App\Kravanh\Domain\DragonTiger\Commands\DragonTigerGameCreateNewCommand;
use App\Kravanh\Domain\DragonTiger\Commands\DragonTigerGameSubmitResultCommand;
use App\Providers\AppServiceProvider;

class DragonTigerServiceProvider extends AppServiceProvider
{
    public function boot(): void
    {
        $domainPath = $this->app->basePath('/app/Kravanh/Domain/DragonTiger/');
        $this->loadMigrationsFrom("$domainPath/Database/migrations");

        $this->commands([
            DragonTigerGameCreateNewCommand::class,
            DragonTigerGameSubmitResultCommand::class,
        ]);

    }
}
