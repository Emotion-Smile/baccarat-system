<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use Illuminate\Support\Facades\DB;

final class WalletBackupAction
{
    public function __invoke(): void
    {
        DB::table('wallet_backups')->delete();
        DB::select(DB::raw("
INSERT INTO wallet_backups (`wallet_id`,`holder_id`,`holder_type`,`balance`,`last_updated_balance`,`created_at`,`updated_at`)
SELECT `id` as wallet_id,`holder_id`,`holder_type`,`balance`,date_format(updated_at,'%Y%m'),`created_at`,`updated_at` FROM wallets;
"));

    }
}
