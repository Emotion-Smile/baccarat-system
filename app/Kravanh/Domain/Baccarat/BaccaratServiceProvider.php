<?php

namespace App\Kravanh\Domain\Baccarat;

use App\Kravanh\Domain\Baccarat\Commands\BaccaratGameCreateNewCommand;
use App\Kravanh\Domain\Baccarat\Commands\BaccaratGameSubmitResultCommand;
use App\Providers\AppServiceProvider;

class BaccaratServiceProvider extends AppServiceProvider
{
    public function boot(): void
    {
        $domainPath = $this->app->basePath('/app/Kravanh/Domain/Baccarat/');
        $this->loadMigrationsFrom("$domainPath/Database/migrations");

        $this->commands([
//            BaccaratGameCreateNewCommand::class,
//            BaccaratGameSubmitResultCommand::class,
        ]);

    }
}
