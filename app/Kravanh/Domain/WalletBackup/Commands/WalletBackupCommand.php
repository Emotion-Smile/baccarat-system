<?php

namespace App\Kravanh\Domain\WalletBackup\Commands;

use App\Kravanh\Domain\WalletBackup\Actions\WalletBackupAction;
use Illuminate\Console\Command;

class WalletBackupCommand extends Command
{

    protected $signature = 'app:wallet-backup';

    protected $description = 'Copy current balance from table wallet to table backup wallet';
    
    public function handle(): int
    {
        (new WalletBackupAction())();
        $this->info('wallet backup completed.');
        return 0;
    }
}
