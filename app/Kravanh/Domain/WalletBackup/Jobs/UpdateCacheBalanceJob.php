<?php

namespace App\Kravanh\Domain\WalletBackup\Jobs;

use App\Kravanh\Domain\WalletBackup\Actions\GetUserBalanceAction;
use App\Kravanh\Domain\WalletBackup\Actions\UpdateCacheBalanceAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateCacheBalanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private array $walletBackups)
    {
    }

    public function handle(): void
    {
        Collection::make($this->walletBackups)
            ->each(function ($wallet) {

                $holderType = $wallet['holder_type'];
                $holderId = $wallet['holder_id'];

                $balance = (new GetUserBalanceAction())(
                    userType: $holderType,
                    id: $holderId
                );

                (new UpdateCacheBalanceAction())(
                    holderId: $holderId,
                    holderType: $holderType,
                    balance: $balance
                );
            });
    }
}
