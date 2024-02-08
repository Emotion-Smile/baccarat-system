<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use DB;
use Illuminate\Support\Collection;

final class GetWalletHaveDifferentBalanceValueAction
{
    public function __invoke(): Collection
    {
        return DB::table('wallet_backups')
            ->whereRaw('balance != cache_balance')
            ->where('is_cache_balance_updated', 1)
            ->get();
    }

}
