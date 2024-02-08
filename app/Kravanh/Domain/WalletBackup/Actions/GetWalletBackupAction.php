<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use App\Kravanh\Domain\WalletBackup\Models\WalletBackup;

final class GetWalletBackupAction
{
    public function __invoke($holder): WalletBackup
    {
        return WalletBackup::query()
            ->where('holder_type', get_class($holder))
            ->where('holder_id', $holder->id)
            ->first();
    }
}
