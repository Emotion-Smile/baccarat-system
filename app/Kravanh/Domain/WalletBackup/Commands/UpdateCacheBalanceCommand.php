<?php

namespace App\Kravanh\Domain\WalletBackup\Commands;

use App\Kravanh\Domain\WalletBackup\Actions\UpdateCacheBalanceDispatcherAction;
use Illuminate\Console\Command;

class UpdateCacheBalanceCommand extends Command
{

    protected $signature = 'app:update-cache-balance {start} {end} {--chunkSize=1000} {--isCacheBalanceUpdated=0}';

    protected $description = 'update cache_balance in table wallet_backup';


    public function handle(): int
    {

        (new UpdateCacheBalanceDispatcherAction())(
            start: $this->argument('start'),
            end: $this->argument('end'),
            chunkSize: $this->option('chunkSize'),
            isCacheBalanceUpdated: $this->option('isCacheBalanceUpdated')
        );

        $this->info('Job dispatched');

        return 0;
    }
}
