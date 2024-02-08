<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetHolderIdDuplicateWalletAction
{
    public function __invoke(): Collection
    {
        $data = DB::select('SELECT holder_id, COUNT(*) as totalWallet FROM wallets GROUP By holder_id HAVING totalWallet > 1');
        return Collection::make($data)->map->holder_id;
    }
}
