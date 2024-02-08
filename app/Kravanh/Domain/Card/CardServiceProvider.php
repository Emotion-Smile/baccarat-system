<?php

namespace App\Kravanh\Domain\Card;

use App\Kravanh\Domain\Card\Concretes\CardService;
use App\Providers\AppServiceProvider;

class CardServiceProvider extends AppServiceProvider
{
    public function boot(): void
    {
        //$domainPath = $this->app->basePath('/app/Kravanh/Domain/Card/');
        //$this->loadMigrationsFrom("$domainPath/Database/migrations");
        $this->app->bind(ICardService::class, CardService::class);
    }
}
