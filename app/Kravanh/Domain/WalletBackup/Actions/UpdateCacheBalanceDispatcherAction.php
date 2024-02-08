<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use App\Kravanh\Domain\WalletBackup\Jobs\UpdateCacheBalanceJob;
use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;
use Closure;

final class UpdateCacheBalanceDispatcherAction
{
    /**
     * @param int $start [202302]
     * @param int $end
     * @param int $chunkSize
     * @param bool $isCacheBalanceUpdated
     * @return void
     */
    public function __invoke(int $start, int $end, int $chunkSize = 1000, bool $isCacheBalanceUpdated = false): void
    {

        WalletBackup::query()
            ->select(['id', 'holder_id', 'holder_type'])
            ->where('last_updated_balance', '>=', $start)
            ->where('last_updated_balance', '<=', $end)
            ->where('is_cache_balance_updated', $isCacheBalanceUpdated)
            ->when($isCacheBalanceUpdated, function ($query) {
                $query->whereRaw('balance != cache_balance');
            })
            ->chunkById($chunkSize, $this->dispatch());
    }

    private function dispatch(): Closure
    {
        return function ($wallets) {
            UpdateCacheBalanceJob::dispatch($wallets->toArray());
        };
    }


}
