<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;

final class IsWalletHasCacheBalanceAction
{
    public function __invoke(int $holderId, string $holderType): bool|null
    {
        $wallet = WalletBackup::query()
            ->select(['holder_id', 'is_cache_balance_updated'])
            ->where('holder_id', $holderId)
            ->where('holder_type', $holderType)
            ->first();

        if (is_null($wallet)) {
            return null;
        }

        return (bool)$wallet->is_cache_balance_updated;
    }
}
