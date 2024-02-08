<?php

namespace App\Kravanh\Domain\WalletBackup\Commands;

use App\Kravanh\Domain\WalletBackup\Actions\GetHolderIdDuplicateWalletAction;
use App\Kravanh\Domain\WalletBackup\Actions\RemoveDuplicateWalletAction;
use Illuminate\Console\Command;

class RemoveDuplicateWalletCommand extends Command
{

    protected $signature = 'app:remove-duplicate-wallet';

    protected $description = 'remove duplicate wallets from wallets table';


    public function handle(): int
    {

        $duplicateCount = (new GetHolderIdDuplicateWalletAction())();

        if (!$duplicateCount->count()) {
            $this->info('No duplicate wallet found');
            return 0;
        }

        $this->info("The Duplicate wallets: ($duplicateCount) ");
//        $confirmed = $this->confirm("Do you want to remove duplicate wallet: ($duplicateCount) ?");
//
//        if (!$confirmed) {
//            $this->info('Operation cancelled');
//            return 0;
//        }

        (new RemoveDuplicateWalletAction())();

        return 0;
    }
}
