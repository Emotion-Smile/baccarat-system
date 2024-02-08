<?php

namespace App\Kravanh\Domain\WalletBackup\Commands;

use App\Kravanh\Domain\WalletBackup\Actions\GetWalletHaveDifferentBalanceValueAction;
use App\Kravanh\Domain\WalletBackup\Actions\SyncBalanceAction;
use Illuminate\Console\Command;

class WalletSyncIssueBalanceCommand extends Command
{

    protected $signature = 'app:wallet-sync-issue-balance';

    protected $description = 'Set balance base on backup balance';


    public function handle(): int
    {

        $wallets = (new GetWalletHaveDifferentBalanceValueAction())();

        $wallets->each(fn($wallet) => (new SyncBalanceAction())($wallet->wallet_id, $wallet->balance));

        $this->info("Balance synced {$wallets->count()}. please run command -> app:update-cache-balance");

        return 0;
    }

}
