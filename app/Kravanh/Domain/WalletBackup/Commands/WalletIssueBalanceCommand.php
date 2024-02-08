<?php

namespace App\Kravanh\Domain\WalletBackup\Commands;

use App\Kravanh\Domain\WalletBackup\Actions\GetWalletHaveDifferentBalanceValueAction;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class WalletIssueBalanceCommand extends Command
{

    protected $signature = 'app:wallet-issue-balance';

    protected $description = 'The wallet has different balance value between balance and cache balance.';


    public function handle(): int
    {
        $wallets = (new GetWalletHaveDifferentBalanceValueAction())();

        if ($wallets->isEmpty()) {
            $this->info('No wallets has balance issue');
            return 0;
        }

        $this->buildTable($this->toRows($wallets));

//        $isConfirmed = $this->confirm('Do you want to fix the balance issue?');
//
//        if (!$isConfirmed) {
//            $this->info('Operation cancelled.');
//            return 0;
//        }

//        $wallets->each(fn($wallet) => (new SyncBalanceAction())($wallet->wallet_id, $wallet->balance));
//        $this->info('Balance synced. please run command -> app:update-cache-balance');

        return 0;
    }

    private function toRows(Collection $wallets): array
    {
        return $wallets->map(function ($wallet) {
            return [
                $wallet->holder_id,
                collect(explode('\\', $wallet->holder_type))->last(),
                $wallet->balance,
                $wallet->cache_balance,
                $wallet->last_updated_balance,
                $wallet->is_cache_balance_updated
            ];
        })->toArray();
    }

    private function buildTable(array $rows): void
    {
        $this->table(
            [
                'holderId',
                'holderType',
                'balance',
                'cacheBalance',
                'date',
                'isUpdated'
            ],
            $rows);

    }
}
