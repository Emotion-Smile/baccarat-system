<?php

namespace App\Kravanh\Domain\WalletBackup;

use App\Kravanh\Domain\WalletBackup\Commands\RemoveDuplicateWalletCommand;
use App\Kravanh\Domain\WalletBackup\Commands\UpdateCacheBalanceCommand;
use App\Kravanh\Domain\WalletBackup\Commands\WalletBackupCommand;
use App\Kravanh\Domain\WalletBackup\Commands\WalletIssueBalanceCommand;
use App\Kravanh\Domain\WalletBackup\Commands\WalletSyncIssueBalanceCommand;
use Illuminate\Support\ServiceProvider;

class WalletBackupServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $domainPath = $this->app->basePath('/app/Kravanh/Domain/WalletBackup/');

        $this->loadMigrationsFrom("$domainPath/Database/migrations");

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

    }

    public function bootForConsole(): void
    {
        $this->commands([
            WalletBackupCommand::class,
            RemoveDuplicateWalletCommand::class,
            UpdateCacheBalanceCommand::class,
            WalletIssueBalanceCommand::class,
            WalletSyncIssueBalanceCommand::class
        ]);
    }
}
