<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;

final class UpdateCacheBalanceAction
{
    public function __invoke(
        int    $holderId,
        string $holderType,
        int    $balance
    ): void
    {
        WalletBackup::query()
            ->where('holder_id', $holderId)
            ->where('holder_type', $holderType)
            ->update([
                'cache_balance' => $balance,
                'is_cache_balance_updated' => true
            ]);
    }
}
